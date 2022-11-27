<?php
class MastodonFeedFix extends Plugin {

	private $host;

	function about() {
		return array(0.1,
			"A plugin to render the articles of the mastodon rss feed correctly",
			"nilsauf");
	}

	function init($host) {
		$this->host = $host;

		$host->add_hook($host::HOOK_RENDER_ARTICLE, $this);
	}

	function hook_render_article($article) {
		return $article;
	}

	function api_version() {
		return 2;
	}

}
?>
