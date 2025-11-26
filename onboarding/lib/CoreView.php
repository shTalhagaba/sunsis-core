<?php

class CoreView
{
	/**
	 * Gets the drop down menu as HTML to display in the view.
	 * 
	 * Instructions on how to add filter saving capabilities to a view:
	 * 
	 * 1) Make sure the form which submits the filtering has an id of "applyFilter"
	 * 2) Add a save button with the following HTML
	 * 
	 * <input type="button" name="saveFilter" value="Save" onclick="doSaveFilter(); return false;"/>
	 * 
	 * 3) Create a block above that form with the following HTML:
	 * 
	 * <div id="div_filters" style="display:none">
	 * <form method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>" id="applySavedFilter">
	 * <input type="hidden" name="_action" value="view_learners" />
	 * <input type="hidden" name="id" value="<?php echo $training; ?>" />
	 * <?php echo $view->getSavedFiltersHTML(); ?>
	 * </form>
	 * 
	 * @return unknown_type
	 */
	public function getSavedFiltersHTML()
	{
		$viewName = get_class($this);
		if($viewName == 'VoltView')
		{
			$viewName = '';
		}
		
		echo '<div id="savedFilterBox">';

		if((isset($this->savedFilters['user']) && sizeof($this->savedFilters['user']) > 0) OR (isset($this->savedFilters['global']) && sizeof($this->savedFilters['global']) > 0))
		{
			echo '<label for="savedFilter">Pre-defined Filters:</label>';
			echo '<div class="selectWrapper"><select name="savedFilter" id="savedFilter" onchange="populateFilter(\'' . $viewName . '\')">';
			echo '<option value="g0">Please select a filter ....</option>';
			
			// do global first
			if(isset($this->savedFilters['global']) && sizeof($this->savedFilters['global']) > 0)
			{
				echo '<optgroup label="Pre-defined filters">';
				foreach($this->savedFilters['global'] AS $key => $filter)
				{
					echo '<option value="g' . $filter['filter_id'] . '">' . $filter['filter_name'] . '</option>';
				}
				echo '</optgroup>';
			}
			
			// do user
			if(isset($this->savedFilters['user']) && sizeof($this->savedFilters['user']) > 0)
			{
				echo '<optgroup label="Your saved filters">';				
				foreach($this->savedFilters['user'] AS $key => $filter)
				{
					echo '<option value="u' . $filter['filter_id'] . '">' . $filter['filter_name'] . '</option>';
				}
				echo '</optgroup>';
			}
			//echo ' <input type="submit" id="savedFilter" value="Filter.." onclick="submitSavedFilter(this); return false;" />';
			echo '</select></div>';
			// re: removed pending the new help system set up.
			// <a href="/do.php?_action=view_help&amp;id=1" class="help" title="Saved filter help" rel="gb_page_center[600, 500]">Help</a>';
		}
		else
		{
			echo '<p>No pre-defined filters</p>';
		}
		echo '</div>';
	}

	protected function getFilterURLBits()
	{
		$validBits = array('_action', 'id');
		$queryBits = explode('?', substr($_SERVER['REQUEST_URI'], 1));
		$queryBits2 = explode('&', $queryBits[1]);
		
		$urlBits = array();
		
		foreach($queryBits2 AS $key => $val)
		{
			$bits = explode('=', $val);
			if(in_array($bits[0], $validBits))
			{
				$urlBits[$bits[0]] = $bits[1]; 
			}
		}

		return $queryBits[0] . '?' . http_build_query($urlBits);
	}
}
