<?php
class home_page_personnel implements IAction
{
	public function execute(PDO $link)
	{
		$type = isset($_REQUEST['type'])?$_REQUEST['type']:'';
		$repo = isset($_REQUEST['repo'])?$_REQUEST['repo']:'';

		if($repo==1)
		{
			//$filerepository = 'class="selected"';
			$welcome = '';
		}
		else
		{
			$welcome = 'class="selected"';
			//$filerepository = '';
		}

		//$_SESSION['bc']->add($link, "do.php?_action=home_page", "Home");

		$ut = (int)$_SESSION['user']->type;

		$test_data = '';
		$legal = '';
		$learner = '';
		$statustotal = '';
		$trainingrecords = '';
		$ilrstatus = '';


		$username=$_SESSION['user']->username;

		// Build Learners per Organisation Bar Chart

			$sql = "select organisations.legal_name, (select count(*) from users where employer_id = organisations.id and type=5) as learners from organisations where organisation_type=2 and organisations.creator='$username'";
			$st = $link->query($sql);
			
			if($st) 
			{
					while( $row = $st->fetch() ) 
					{	
						if ($row['learners']!=0) 
						{
						$legal .= "'".addslashes((string)$row['legal_name'])."',";			
						$learner .= $row['learners'].",";	
						}								
					}	
			}






		$stat_graphs = "<graph>";
		$stat_graphs = <<<JS
			
							/*******Build Learner Status Pie Chart******************/
	Highcharts.setOptions({
lang: 
{
	exportButtonTitle:"Export chart as Image"
}
});
								
var chart;
$(document).ready(function() {
chart = new Highcharts.Chart({
chart: 
{
	renderTo: 'learnerStatus',
	height: 300,
	width: 320,
    borderColor: '#727375',
    borderWidth: 0,
    backgroundColor: null,
    defaultSeriesType: 'pie'
}, 		   		  		
title: 
{
	text: null,
	floating: false,
	align: 'center',
	style: 
	{
		fontFamily: 'arial',
		fontSize: '12px',
		color: 'black',
		fontWeight: 'bold'
	}
},
legend: 
{
	layout: 'vertical'
},
credits: 
{
	enabled: false
},
colors: 
[
	'#151B8D',
	'#437C17',
	'#eeeeee' 
],
tooltip:
{
	formatter: function() 
	{
		return + this.y+ ' Learners';
	}
},
plotOptions: 
{
	pie: 
	{
		size:200,	
		dataLabels: 
		{
			distance: -50,
			color: 'white',
			fontWeight: 'bold',
			formatter: function() 
			{
				return this.y;
			}
		}
	}
},
series: 
[{
	cursor: 'pointer',
	showInLegend: true,
	point: 
	{
		events: 
		{
			click: function() 
			{
				alert (this.name)
				switch (this.name)	
				{
					//Behind filter
					case 'Behind':
					window.location.href='do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_progress=2';
					break;

					//On track filter
					case 'On Track':
					window.location.href='do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_progress=1';
					break;
																						
					default:
					break;
				}
			}
		}
	},
	type: 'pie',
	name: 'Status Total',
	data:  [$statustotal]
}],
exporting: 
{
	enabled: true,
	width: 300,
	filename: 'Learner Status',
	buttons: 
	{
		printButton: 
		{
			enabled: false
		},
		exportButton: 
		{
			menuItems: 
			[
				{text: 'Download chart as PNG'}, 
                {text: 'Download chart as JPG'}, 
                {text: 'Download chart as PDF'}, 
                {text: 'Download chart as SVG'}
			]
		}
	}
},
navigation: 
{
	buttonOptions: 
	{
		verticalAlign: 'top',
		y: 0
	},
	menuItemStyle: 
    {
 		width:150,
		borderLeft: '20px solid #E0E0E0'
	}
}

});

/*******Build Learners per Organisation Bar Chart******************/
   
chart = new Highcharts.Chart({
chart: 
{
	renderTo: 'learnerPerOrganisation',
	height: 300,
	width: 320,
	borderColor: '#727375',
	borderWidth: 0,
	backgroundColor: null,
	defaultSeriesType: 'bar',
	inverted: true,
	Style:
	{
		fontFamily: 'arial',
 		fontSize:'11px'
	}
},
title: 
{
	text: null,
	floating: false,
	align: 'center',
	style:
	{
		fontFamily: 'arial',
		fontSize: '12px',
		color: 'black',
		fontWeight: 'bold'
	}
},
credits: 
{
	enabled: false
},
xAxis: 
{
	labels: 
	{
		enabled: true,
		style: 
		{
			fontFamily: 'arial',
			fontSize: '10px',
			color: 'gray'
		}
	}
},     			
categories: 
	[$legal],
title: 
{
	enabled: false
},
yAxis: 
{
	min: 0,
	title: 
	{
		align: 'low',
		text: null,
       	style: 
       	{
			fontFamily: 'arial',
			fontSize: '12px',
			color: 'gray'
       	}
	}
},
legend: 
{
	enabled: false
},
colors: 
	['#437C17'],
tooltip: 
{
	formatter: function() { return ''+ this.y +' Learners';}
},
plotOptions: 
{
	column: 
	{
		pointPadding: 0.2,
		borderWidth: 0
	}
},
series: 
[{
	name: 'Learners',
	data: [$learner]
}],
exporting: 
{
	enabled: true,
	width: 500,
	filename: 'Learners-Per-Organisation',
	buttons: 
	{
		printButton: 
		{
			enabled: false
		}
	}
},
navigation: 
{
	buttonOptions: 
 	{
		verticalAlign: 'top',
    	y: 0
	}
}

});


							/*******Build ILR status Graph with HTML table	******************/
Highcharts.visualize = function(table, options) 
{
	// the categories
	options.xAxis.categories = [];
 	$('tbody th', table).each( function(i) 
 	{
		options.xAxis.categories.push(this.innerHTML);
 	});
   
	// the data series
	options.series = [];
 	$('tr', table).each( function(i) 
	{
		var tr = this;
		$('th, td', tr).each( function(j) 
		{
			if (j > 0)
			{ // skip first column
				if (i == 0)
				{ // get the name and init the series
               		options.series[j - 1] =
					{
						name: null,
						data: [],

						point:
						{
                			events:
                			{
								click: function()
								{
									switch(this.name)
									{
									case 'ilr.is_valid':
									//Not Valid ILR
                        			window.location.href='do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_funding=2';
									break;

									case 'ilr.is_valid':
                        			//Valid ILR
                        			window.location.href='do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_funding=1';
									break;
									}
                    			}
							}
						}
					}
            	} else 
            	{ // add values
               		options.series[j - 1].data.push(parseFloat(this.innerHTML));
            	}
			}
		});
	});
   
   var chart = new Highcharts.Chart(options);
}
							
var table = document.getElementById('ILRdatatable'),
options = {
chart: 
{
	renderTo: 'ILR',
	defaultSeriesType: 'column',
    height: 300,
    width: 320,
    backgroundColor: null,
    borderColor: '#727375',
    borderWidth: 0
},
title: 
{
    text: null,
    floating: false,
    align: 'center',
    style: 
    {
		fontFamily: 'arial',
      	fontSize: '12px',
      	color: 'black',
      	fontWeight: 'bold'
    }
},
colors: 
	['#437C17'],
credits: 
{
	enabled: false
},
legend: 
{
	enabled: false
},
xAxis: 
{
	labels: 
    {
    style: 
    {
    	fontFamily: 'arial',
       	fontSize: '12px',
       	color: 'gray'
	}
	}
},
yAxis: 
{
	style: 
	{
		fontFamily: 'arial',
		fontSize: '11px',    	
		color: 'gray'
    },
	title: 
	{
		align: 'center',
		text: "No of ILRs",
		style: 
	{
		fontFamily: 'arial',
		fontSize: '11px',    	
		color: 'black'
	}
    }
},
tooltip: 
{
     formatter: function() 
     {
		return +this.y +' '+ 'ILRs';
     }
},
exporting: 
{
    enabled: false,
    width: 500,
    filename: 'ILR-Validation',
    buttons: 
    {
		printButton: 
    	{
			enabled: false
    	}
	}
},
navigation: 
{
 	buttonOptions: 
 	{
		verticalAlign: 'top',
		y: 0
    }
}
};
                   
Highcharts.visualize(table, options);
});

 		
JS;

		require_once('tpl_home_page_personnel.php');
	}



}
?>