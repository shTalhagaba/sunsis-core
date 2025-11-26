<div class="box box-primary">
	<div class="box-header with-border lead">
		<?php
		echo $company->legal_name;
		echo '<h5 class="pull-right" style="display: inline;"> ';
		$trophy = $company->company_rating;
		if($trophy == 'G')
			echo '<i title="GOLD Employer" class="fa fa-trophy fa-3x" style="color: gold;"></i>';
		elseif($trophy == 'S')
			echo '<i title="Silver Employer" class="fa fa-trophy fa-3x" style="color: silver;"></i>';
		elseif($trophy == 'B')
			echo '<i title="Bronze Employer" class="fa fa-trophy fa-3x" style="color: #cd7f32;"></i>';
		echo '</h5>';
		$company_size_options = ['1' => 'Levy', '2' => 'Non Levy', '3' => 'SME'];
		?>
	</div>
	<div class="box-body">
		<div>
			<?php
			echo isset($company_size_options[$company->site_employees]) ? 'Company Size: ' . $company_size_options[$company->site_employees] . '<br>' : '';
			echo $company_location->address_line_1 != '' ? $company_location->address_line_1 . '<br>' : '';
			echo $company_location->address_line_2 != '' ? $company_location->address_line_2 . '<br>' : '';
			echo $company_location->address_line_3 != '' ? $company_location->address_line_3 . '<br>' : '';
			echo $company_location->address_line_4 != '' ? $company_location->address_line_4 . '<br>' : '';
			echo $company_location->postcode != '' ? $company_location->postcode . '<br>' : '';
			?>
		</div>
		<iframe style="background-color: #ffffff;"
		        src="https://maps.google.co.uk/maps?q=<?php echo $company_location->postcode; ?>&amp;ie=UTF8&amp;hq=&amp;hnear=B1 2HF,+United+Kingdom
													&amp;gl=uk&amp;t=m&amp;vpsrc=0&amp;z=14&amp;iwloc=A&amp;output=embed"
		        frameborder="0" marginwidth="0" marginheight="0" scrolling="no" align="left"
		        width="100%" height="250"></iframe>
	</div>
</div>