<?php
class MastodonFeedFix extends Plugin {

	private $api_instance = "/api/v2/instance";
	private $source_url = "https://github.com/mastodon/mastodon";
	private $maxTitleLength = 50;
	private $host;
	private $hostNames;

	function about() {
		return array(
			0.1,
			"A plugin to render the articles of the mastodon rss feed correctly",
			"nilsauf"
		);
	}

	function init($host) {
		$this->host = $host;
		$this->hostNames = array();
		$host->add_hook($host::HOOK_RENDER_ARTICLE, $this);
	}

	private function get_api_instance_url($scheme, $hostname) {
		return $scheme . "://" . $hostname . $this->api_instance;
	}

	private function is_from_mastodon($article) {
		$url = $article["link"];
		$url_info = parse_url($url);
		$hostname = $url_info["host"];

		if(array_key_exists($hostname, $this->hostNames)) {
			return $this->hostNames[$hostname];
		}

		$scheme = $url_info["scheme"];
		$api_instance_url = $this->get_api_instance_url($scheme, $hostname);

		$api_info = file_get_contents($api_instance_url);
		$instance_info = json_decode($api_info);

		$this->hostNames[$hostname] = $instance_info->source_url == $this->source_url;

		return $this->hostNames[$hostname];
	}

  
	function hook_render_article($article) {
		if($this->is_from_mastodon($article)) {
			$newTitle = mb_substr($article["content"], 0, $this->maxTitleLength);
			$article["title"] = $newTitle;
		}

		return $article;
	}

	function api_version() {
		return 2;
	}

}
?>
