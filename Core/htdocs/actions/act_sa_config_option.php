<?php
class sa_config_option extends ActionController
{
	public function indexAction(PDO $link)
	{
		$authorised = $_SESSION['user']->isAdmin() && (SOURCE_BLYTHE_VALLEY || SOURCE_LOCAL);
		if (!$authorised) {
			throw new UnauthorizedException();
		}

		$employers = SystemConfig::get("smartassessor.config.employers");
		$learners = SystemConfig::get("smartassessor.config.learners");
		$assessors = SystemConfig::get("smartassessor.config.assessors");
		$review = SystemConfig::get("smartassessor.config.review");

		// Normalise
		$employers = $employers ? '1':'0';
		$learners = $learners ? '1':'0';
		$assessors = $assessors ? '1':'0';
                                $review = $review ? '1':'0';
                                
		include("smartassessor/settings/tpl_config_option_read.php");
	}

	public function editAction(PDO $link)
	{
		$authorised = $_SESSION['user']->isAdmin() && (SOURCE_BLYTHE_VALLEY || SOURCE_LOCAL);
		if (!$authorised) {
			throw new UnauthorizedException();
		}
                        
		$employers = SystemConfig::get("smartassessor.config.employers");
		$learners = SystemConfig::get("smartassessor.config.learners");
		$assessors = SystemConfig::get("smartassessor.config.assessors");
		$review = SystemConfig::get("smartassessor.config.review");

		// Normalise
		$employers = $employers ? '1':'0';
		$learners = $learners ? '1':'0';
		$assessors = $assessors ? '1':'0';
                                $review = $review ? '1':'0';

		include("smartassessor/settings/tpl_config_option_edit.php");
	}


	public function saveAction(PDO $link)
	{
		$authorised = $_SESSION['user']->isAdmin() && (SOURCE_BLYTHE_VALLEY || SOURCE_LOCAL);
		if (!$authorised) {
			throw new UnauthorizedException();
		}
                           
		$employers = $this->_getParam("employers");
		$learners = $this->_getParam("learners");
		$assessors = $this->_getParam("assessors");
		$review = $this->_getParam("review");
		

		 // Normalise
		$employers = $employers ? '1':'0';
		$learners = $learners ? '1':'0';
		$assessors = $assessors ? '1':'0';
		$review = $review ? '1':'0';
                
		SystemConfig::set("smartassessor.config.employers", $employers);
		SystemConfig::set("smartassessor.config.learners", $learners);
		SystemConfig::set("smartassessor.config.assessors", $assessors);
		SystemConfig::set("smartassessor.config.review", $review);
		
	}

	
}