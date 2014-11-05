<?php
/**
 * Extended class to override the time_created
 */
class ElggGCUser extends ElggUser {

	/**
	 * Set subtype to GCuser.
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['subtype'] = "GCuser";
		$this->attributes['feed_viewed'] = NULL;
		$this->attributes['feed_viewed_previous'] = NULL;
	}

	public function setFeedViewed($name) {
		$this->feed_viewed = $name;
	}

	public function setFeedViewedPrevious($name) {
		$this->feed_viewed_previous = $name;
	}
}
