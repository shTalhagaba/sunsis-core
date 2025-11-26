<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $page_title;?></title>
<link rel="stylesheet" href="/common.css" type="text/css"/>
<link rel="stylesheet" href="/css/reports_css.css" type="text/css"/>
<script src="/js/jquery.min.js" type="text/javascript"></script>
<script src="/common.js" type="text/javascript"></script>
<script language="javascript" src="/js/highcharts.js" type="text/javascript"></script>
<script language="javascript" src="/js/highcharts-more.js" type="text/javascript"></script>
<script src="/js/exporting.js" type="text/javascript"></script>

<!-- Calendar popup: credit to Matt Kruse (www.javascripttoolbox.com) -->
<script type="text/javaScript" src="/calendarPopup/CalendarPopup.js"></script>
<script language="JavaScript">
	var calPop = new CalendarPopup("calPop1");
	calPop.showNavigationDropdowns();
	document.write(getCalendarStyles());
</script>


<script type="text/javascript">
var data_exists="";//if graph data exists or not
var colors = Highcharts.getOptions().colors;

var report_type = "<?php echo $report_type; ?>";
var page_mode = "<?php echo page_mode; ?>";


var line_chart_div_id = "container_line";
var line_chart_title="";
var line_chart_subtitle="";
var line_chart_x_axis_title="";
var line_chart_x_axis_categories=[];
var line_chart_y_axis_title="";
var line_chart_series_title="";
var line_chart_series_data=[];
var line_chart_drilldown_details=[];
var line_chart_info_arr=[];
var line_chart_color = colors[0];



var bar_chart_div_id = "container";
var bar_chart_title="";
var bar_chart_subtitle="";
var bar_chart_x_axis_title="";
var bar_chart_x_axis_categories=[];
var bar_chart_y_axis_title="";
var bar_chart_series_title="";
var bar_chart_series_data=[];
var bar_chart_drilldown_details=[];
var bar_chart_info_arr=[];
var bar_chart_tooltip_format = "";
var bar_chart_drillup_tooltip_format = "";
var bar_chart_drilldown_tooltip_format = "";

function barChartTooltip(obj)
{
    //alert('barChartTooltip');
    //console.log(obj);
    //alert('bar_chart_tooltip_format = '+bar_chart_tooltip_format);


    var point = obj.point;

    if (point.drilldown)
    {
        bar_chart_tooltip_format = bar_chart_drillup_tooltip_format
    }
    else
    {
        bar_chart_tooltip_format = bar_chart_drilldown_tooltip_format;
    }
    bar_chart_tooltip_format = bar_chart_tooltip_format.replace("ttl_title",obj.key);
    bar_chart_tooltip_format = bar_chart_tooltip_format.replace("ttl_value",obj.y);

    return bar_chart_tooltip_format;
}


var pie_chart_div_id = "container_pie";
var pie_chart_title="";
var pie_chart_subtitle="";
var pie_chart_x_axis_title="";
var pie_chart_x_axis_categories=[];
var pie_chart_y_axis_title="";
var pie_chart_series_title="";
var pie_chart_series_data=[];
var pie_chart_drilldown_details=[];
var pie_chart_info_arr=[];



var speedo_chart_div_id = "container_speedo";
var speedo_chart_title="";
var speedo_chart_subtitle="";
var speedo_chart_series_data=[];

function load_graphs()
{
//alert('function load_graphs');
    $.ajax({
        type:"GET",
        data:"generate_report=generate_report&report_type="+report_type+"&"+$('#report_criteria_form').serialize(),
        url:"do.php?_action=ajax_sla_kpi_reports",
        dataType : 'json',
        beforeSend:function(data)
        {
            //alert('before send');
            $('#sp_loading_graphs').show();
        },
        success:function(response)
        {
        //alert(response.result);
        if(response.result == "no_data")
        {
            //alert('Sorry, no data found !');
            $('#div_no_data_found').show();
            $('#sp_loading_graphs').hide();
            $('#container_line').hide();
            $('#div_two_graphs').hide();
            $('#upper_div').hide();
            data_exists="false";
        }
        else
        {
            data_exists="true";
            var graph_type = $('#graph_type').val();
            //$('#container_line').show();
            show_hide_graph_types(graph_type);
            $('#upper_div').show();

            //alert('response = '+response);
            //console.log(response);


        ////////////////set data for line chart

        line_chart_info_arr['chart_div_id'] = line_chart_div_id;
        line_chart_info_arr['chart_title'] = line_chart_title = response.line_chart_details.title;
        line_chart_info_arr['chart_subtitle'] = line_chart_subtitle = response.line_chart_details.subtitle;
        line_chart_info_arr['chart_x_axis_title'] = line_chart_x_axis_title = response.line_chart_details.x_axis_title;
        line_chart_info_arr['chart_x_axis_categories'] = line_chart_x_axis_categories = response.line_chart_details.x_axis_categories;
        line_chart_info_arr['chart_y_axis_title'] = line_chart_y_axis_title = response.line_chart_details.y_axis_title;
        line_chart_info_arr['chart_series_title'] = line_chart_series_title = response.line_chart_details.series_title;
        line_chart_info_arr['chart_series_data'] = line_chart_series_data = response.line_chart_details.series_data;
        line_chart_info_arr['chart_drilldown_details'] = line_chart_drilldown_details = response.line_chart_details.drilldown_details;



        $('#'+line_chart_div_id).highcharts().series[0].update({
              //color:colors[0],
              point:{
                  events:{
                      click:function(){
                          //alert('hey clicked');

                          var drilldown = this.drilldown;
                          //alert('this');
                          //console.log(this);
                          if (drilldown) { // drill down
                              //alert('in drilldown');
                              //console.log(drilldown);

                              setMyChart($('#'+line_chart_div_id).highcharts(), drilldown.name, drilldown.categories, drilldown.data, line_chart_color, 'line_drilldown_x_axis_title', chartType="line");
                          }
                      }
                  }
              }
          });

        $('#'+line_chart_div_id).highcharts().setTitle({ text: line_chart_title }, { text: line_chart_subtitle });

        //alert(response.line_chart_details.x_axis_title);
        //console.log($('#'+line_chart_div_id).highcharts());

        $('#'+line_chart_div_id).highcharts().xAxis[0].setTitle({
            text: line_chart_x_axis_title
        });

        $('#'+line_chart_div_id).highcharts().xAxis[0].categories = line_chart_x_axis_categories;

        $('#'+line_chart_div_id).highcharts().yAxis[0].setTitle({
            text: line_chart_y_axis_title
        });

        $('#'+line_chart_div_id).highcharts().series[0].update({
            name: line_chart_series_title,
            color:line_chart_color
        });
        //alert(response.line_chart_details.tooltip_suffix)
        $('#'+line_chart_div_id).highcharts().series[0].setData(line_chart_series_data);


        if(typeof response.line_chart_details.drilldown_details !== 'undefined')//if drilldown details exists
        {
        for(var i=0; i<line_chart_series_data.length; i++)
        {
            var x_cat_name = line_chart_x_axis_categories[i];


            var drilldown_name = line_chart_drilldown_details[x_cat_name].drill_down.name;
            var drilldown_x_axis_title = line_chart_drilldown_details[x_cat_name].drill_down.x_axis_title;
            //alert('drilldown_name = '+drilldown_name);

            var drilldown_categories = line_chart_drilldown_details[x_cat_name].drill_down.categories;

            var drilldown_data = line_chart_drilldown_details[x_cat_name].drill_down.data;

            $('#'+line_chart_div_id).highcharts().series[0].data[i].update({
              //color:colors[i],

              events:{
                click: function() {

                    var drilldown = this.drilldown;
                    //alert('this');
                    //console.log(this);
                    if (drilldown) { // drill down
                        //alert('in drilldown');
                        //console.log(drilldown);

                        setMyChart($('#'+line_chart_div_id).highcharts(), drilldown.name, drilldown.categories, drilldown.data, line_chart_color, drilldown_x_axis_title, chartType="line");
                    }
                }
              },
              drilldown: {
                name: drilldown_name,
                categories: drilldown_categories,
                data: drilldown_data//,
                //color: colors[i]
              }
            });

        }
        }

         ////////////////set data for bar chart

        bar_chart_info_arr['chart_div_id'] = bar_chart_div_id;
        bar_chart_info_arr['chart_title'] = bar_chart_title = response.bar_chart_details.title;
        bar_chart_info_arr['chart_subtitle'] = bar_chart_subtitle = response.bar_chart_details.subtitle;
        bar_chart_info_arr['chart_x_axis_title'] = bar_chart_x_axis_title = response.bar_chart_details.x_axis_title;
        bar_chart_info_arr['chart_x_axis_categories'] = bar_chart_x_axis_categories = response.bar_chart_details.x_axis_categories;
        bar_chart_info_arr['chart_y_axis_title'] = bar_chart_y_axis_title = response.bar_chart_details.y_axis_title;
        bar_chart_info_arr['chart_series_title'] = bar_chart_series_title = response.bar_chart_details.series_title;
        bar_chart_info_arr['chart_series_data'] = bar_chart_series_data = response.bar_chart_details.series_data;
        bar_chart_info_arr['chart_drilldown_details'] = bar_chart_drilldown_details = response.bar_chart_details.drilldown_details;
        bar_chart_drillup_tooltip_format = response.bar_chart_details.bar_chart_drillup_tooltip_format;
        bar_chart_drilldown_tooltip_format = response.bar_chart_details.bar_chart_drilldown_tooltip_format;

        $('#'+bar_chart_div_id).highcharts().series[0].update({
              //color:"green",
              point:{
                  events:{
                      click:function(){
                          //alert('hey clicked');

                          var drilldown = this.drilldown;
                          //alert('this');
                          //console.log(this);
                          if (drilldown) { // drill down
                              //alert('in drilldown');
                              //console.log(drilldown);

                              setMyChart($('#'+bar_chart_div_id).highcharts(), drilldown.name, drilldown.categories, drilldown.data, drilldown.color, 'bar_drilldown_x_axis_title',chartType="bar");
                          }
                      }
                  }
              }
          });

        $('#'+bar_chart_div_id).highcharts().setTitle({ text: bar_chart_title }, { text: bar_chart_subtitle });

        $('#'+bar_chart_div_id).highcharts().xAxis[0].setTitle({
            text: bar_chart_x_axis_title
        });

        $('#'+bar_chart_div_id).highcharts().xAxis[0].categories = bar_chart_x_axis_categories;

        $('#'+bar_chart_div_id).highcharts().yAxis[0].setTitle({
            text:bar_chart_y_axis_title
        });

        $('#'+bar_chart_div_id).highcharts().series[0].update({
            name:bar_chart_series_title
        });


        $('#'+bar_chart_div_id).highcharts().series[0].setData(bar_chart_series_data);

        //if drilldown details exists
        if(typeof response.bar_chart_details.drilldown_details !== 'undefined')
        {

        for(var i=0; i<bar_chart_series_data.length; i++)
        {
            var x_cat_name = bar_chart_x_axis_categories[i];
            var drilldown_name = bar_chart_drilldown_details[x_cat_name].drill_down.name;
            var drilldown_x_axis_title = bar_chart_drilldown_details[x_cat_name].drill_down.x_axis_title;
            //alert('drilldown_name = '+drilldown_name);

            var drilldown_categories = bar_chart_drilldown_details[x_cat_name].drill_down.categories;

            var drilldown_data = bar_chart_drilldown_details[x_cat_name].drill_down.data;

            $('#'+bar_chart_div_id).highcharts().series[0].data[i].update({
              color:colors[i],

              events:{
                click: function() {

                    var drilldown = this.drilldown;
                    //alert('this');
                    //console.log(this);
                    if (drilldown) { // drill down
                        //alert('in drilldown');
                        //console.log(drilldown);

                        setMyChart($('#'+bar_chart_div_id).highcharts(), drilldown.name, drilldown.categories, drilldown.data, drilldown.color, drilldown_x_axis_title, chartType="bar");
                    }
                    /*else
                    {
                        alert('in else');
                    }*/
                }
              },
              drilldown: {
                name: drilldown_name,
                categories: drilldown_categories,
                data: drilldown_data,
                color: colors[i]
              }
            });
        }
        }
       //console.log($('#'+bar_chart_div_id).highcharts());


       //$('#'+bar_chart_div_id).highcharts().series[0].update({
         //   tooltip:{
           //     valueSuffix:response.bar_chart_details.tooltip_suffix,
                /*formatter: function() {
                    var point = this.point,
                        s = this.x +': <b>'+ this.y +'</b> '+bar_chart_details.tooltip_suffix+'<br/>';
                    if (point.drilldown) {
                        s += 'Click to view '+ point.category +' versions';
                    } else {
                        s += 'Click to return to browser brands';
                    }
                    return s;
                }*/
            //}
        //});


        ////////////////set data for pie chart
        //console.log(response);
        pie_chart_info_arr['chart_div_id'] = pie_chart_div_id;
        pie_chart_info_arr['chart_title'] = pie_chart_title = response.pie_chart_details.title;
        pie_chart_info_arr['chart_subtitle'] = pie_chart_subtitle = response.pie_chart_details.subtitle;
        pie_chart_info_arr['chart_x_axis_title'] = pie_chart_x_axis_title = response.pie_chart_details.x_axis_title;
        pie_chart_info_arr['chart_x_axis_categories'] = pie_chart_x_axis_categories = response.pie_chart_details.x_axis_categories;
        pie_chart_info_arr['chart_y_axis_title'] = pie_chart_y_axis_title = response.pie_chart_details.y_axis_title;
        pie_chart_info_arr['chart_series_title'] = pie_chart_series_title = response.pie_chart_details.series_title;
        pie_chart_info_arr['chart_series_data'] = pie_chart_series_data = response.pie_chart_details.series_data;
        pie_chart_info_arr['chart_drilldown_details'] = pie_chart_drilldown_details = response.pie_chart_details.drilldown_details;


        $('#'+pie_chart_div_id).highcharts().series[0].update({
              //color:"green",
              point:{
                  events:{
                      click:function(){
                          //alert('hey clicked');

                          var drilldown = this.drilldown;
                          //alert('this');
                          //console.log(this);
                          if (drilldown) { // drill down
                              //alert('in drilldown');
                              //console.log(drilldown);

                              setMyPieChart($('#'+pie_chart_div_id).highcharts(), drilldown.name, drilldown.data, drilldown.color);
                          }
                          /*else
                          {
                              alert('in else');
                          }*/
                      }
                  }
              }
        });

        $('#'+pie_chart_div_id).highcharts().setTitle({ text: pie_chart_title }, { text: pie_chart_subtitle });

        var piechartData=[];

        $.each(pie_chart_series_data, function(key,value) {
           //alert('key='+key+" value="+value);
           var point = [];
           point.push(key);
           value = parseFloat(value);
           point.push(value);
           piechartData.push(point);
        });


        $('#'+pie_chart_div_id).highcharts().series[0].setData(piechartData);

        //if drilldown details exists
        if(typeof response.pie_chart_details.drilldown_details !== 'undefined')
        {
        var i=0;
        $.each(pie_chart_series_data, function(key,value) {

           var x_cat_name = key;
           //alert("x_cat_name = "+x_cat_name);
           var drilldown_name = pie_chart_drilldown_details[x_cat_name].drill_down.name;
           //var drilldown_x_axis_title = pie_chart_drilldown_details[x_cat_name].drill_down.x_axis_title;
           //alert('drilldown_name = '+drilldown_name);
           var drilldown_data = pie_chart_drilldown_details[x_cat_name].drill_down.data;

           $('#'+pie_chart_div_id).highcharts().series[0].data[i].update({
                name : x_cat_name,
                y: value,
                //color: colors[0],
                drilldown: {
                    name: drilldown_name,
                    data: drilldown_data//,
                    //color: colors[0]
                },
                events:{
                click: function() {

                    var drilldown = this.drilldown;
                    //alert('this');
                    //console.log(this);
                    if (drilldown) { // drill down
                        //alert('in drilldown');
                        //console.log(drilldown);

                        setMyPieChart($('#'+pie_chart_div_id).highcharts(), drilldown.name, drilldown.data, drilldown.color=null);
                    }
                }
              }
           });

           i++;
        });
        }

        ////////////////set data for speedo chart
        speedo_chart_title = response.speedo_chart_details.title;
        speedo_chart_subtitle = response.speedo_chart_details.subtitle;
        speedo_chart_series_data = [response.speedo_chart_details.series_data];
        //alert('speedo_chart_series_data = '+speedo_chart_series_data);

        if(speedo_chart_series_data != '')
        {
            $('#'+speedo_chart_div_id).highcharts().setTitle({ text: speedo_chart_title }, { text: speedo_chart_subtitle });
            //$('#'+speedo_chart_div_id).highcharts().series[0].setData(speedo_chart_series_data);
            $('#'+speedo_chart_div_id).highcharts().yAxis[0].options.max = response.speedo_chart_details.max_value;

            $('#'+speedo_chart_div_id).highcharts().series[0].update({
                name:speedo_chart_title,
                data: speedo_chart_series_data
            });
        }
        else
        {
            $('#container_speedo').hide();
        }
        //console.log($('#'+speedo_chart_div_id).highcharts());

        $('#div_data_table').html(response.data_table);

        $('#sp_loading_graphs').hide();
       }
    }
  });
}

function setMyPieChart(chart_obj, name, data, color)
{
  //alert('setMyPieChart');
  //console.log(data);

  chart_obj.series[0].remove(false);

  var piechartData=[];

  $.each(data, function(key,value) {
     //alert('key='+key+" value="+value);
     var point = [];
     point.push(key);
     value = parseFloat(value);
     point.push(value);
     piechartData.push(point);
  });


  chart_obj.addSeries({
  name: name,
  data: piechartData,
  color: color,
  showInLegend: false
  }, false);



    var i=0;
    $.each(data, function(key,value) {
         var x_cat_name = key;
         //alert("x_cat_name = "+x_cat_name);

         if(typeof pie_chart_drilldown_details[x_cat_name] !== 'undefined')
         {
             var drilldown_name = pie_chart_drilldown_details[x_cat_name].drill_down.name;


             //var drilldown_x_axis_title = pie_chart_drilldown_details[x_cat_name].drill_down.x_axis_title;
             //alert('drilldown_name = '+drilldown_name);
             var drilldown_data = pie_chart_drilldown_details[x_cat_name].drill_down.data;

             chart_obj.series[0].data[i].update({
                  name : x_cat_name,
                  y: value,
                  //color: colors[0],
                  drilldown: {
                      name: drilldown_name,
                      data: drilldown_data//,
                      //color: colors[0]
                  }
             });
         }
         i++;

    });


  //chart_obj.series[0].setData(piechartData);
  chart_obj.redraw();
  //alert('hey here');
  chart_obj.series[0].update({
        color:color,
        point:{
            events:{
                click:function(){
                    //alert('hey clicked');

                    var drilldown = this.drilldown;
                    //alert('this');
                    //console.log(this);
                    if (drilldown) { // drill down
                        //alert('in after drilldown drilldown.name = '+drilldown.name);
                        //console.log(drilldown);

                        setMyPieChart(chart_obj, drilldown.name, drilldown.data, color);
                    }
                    else
                    {
                        //alert('setMyPieChart in after else');

                        setMyPieChart(chart_obj, pie_chart_series_title, pie_chart_series_data, color);
                    }
                }
            }
        }
    });
}


function setMyChart(chart_obj, name, categories, data, color, x_axis_title, chartType)
{
  //alert('setMyChart12');
  chart_obj.xAxis[0].setTitle({
      text: x_axis_title
  });
  chart_obj.xAxis[0].setCategories(categories, false);
  chart_obj.series[0].remove(false);
  chart_obj.addSeries({
  name: name,
  data: data,
  color: color,
  showInLegend: false
  }, false);
  chart_obj.redraw();
  chart_obj.series[0].update({
        color:color,
        point:{
            events:{
                click:function(){
                    //alert('hey clicked');

                    var drilldown = this.drilldown;
                    //alert('this');
                    //console.log(this);
                    if (drilldown) { // drill down
                        //alert('in drilldown drilldown.name = '+drilldown.name);
                        //console.log(drilldown);

                        setMyChart(chart_obj, drilldown.name, drilldown.categories, drilldown.data, drilldown.color, x_axis_title, chartType);
                    }
                    else
                    {
                        //alert('in else no drilldown bar_chart_x_axis_title = '+bar_chart_x_axis_title);
                        if(chartType == "bar")
                        {
                            setMyChart(chart_obj, bar_chart_series_title, bar_chart_x_axis_categories, bar_chart_series_data, color, bar_chart_x_axis_title, chartType);
                        }
                        else if(chartType == "line")
                        {
                            setMyChart(chart_obj, line_chart_series_title, line_chart_x_axis_categories, line_chart_series_data, color, line_chart_x_axis_title, chartType);
                        }

                    }

                }
            }
        }
    });
}


$(document).ready(function() {
//alert('page_loaded');
      load_graphs();

      var categories = ['Q1', 'Q2', 'Q3', 'Q4', 'Q5'],
            drill_categories = ['Employer', 'Training Provider', 'Contract', 'Assessor'],
            name = 'Achievers',
            data = [{
                    y: 55.11,
                    color: colors[0],
                    drilldown: {
                        name: 'MSIE versions',
                        categories: drill_categories,
                        data: [10.85, 7.35, 33.06, 2.81],
                        color: colors[0]
                    }
                }, {
                    y: 21.63,
                    color: colors[1],
                    drilldown: {
                        name: 'Firefox versions',
                        categories: drill_categories,
                        data: [0.20, 0.83, 1.58, 13.12],
                        color: colors[1]
                    }
                }, {
                    y: 11.94,
                    color: colors[2],
                    drilldown: {
                        name: 'Chrome versions',
                        categories: drill_categories,
                        data: [0.12, 0.19, 0.12, 0.36],
                        color: colors[2]
                    }
                }, {
                    y: 7.15,
                    color: colors[3],
                    drilldown: {
                        name: 'Safari versions',
                        categories: drill_categories,
                        data: [4.55, 1.42, 0.23, 0.21],
                        color: colors[3]
                    }
                }, {
                    y: 2.14,
                    color: colors[4],
                    drilldown: {
                        name: 'Opera versions',
                        categories: drill_categories,
                        data: [ 0.12, 0.37, 1.65, 5.8],
                        color: colors[4]
                    }
                }];




      /*function setChart(name, categories, data, color) {
            alert('setChart');
			chart.xAxis[0].setCategories(categories, false);
			chart.series[0].remove(false);
			chart.addSeries({
				name: name,
				data: data,
				color: color || 'red',
                showInLegend: false
			}, false);
			chart.redraw();
        }*/

        var chart = $('#'+bar_chart_div_id).highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: 'Bar chart title'
            },
            subtitle: {
                text: 'Click the columns to view versions. Click again to view brands.'
            },
            xAxis: {
                categories: categories,
                title: {
                    text: 'bar x-title'
                }
            },
            yAxis: {
                title: {
                    text: 'bar y-title'
                }
            },
            plotOptions: {
                column: {
                    cursor: 'pointer',
                    /*point: {
                        events: {
                            click: function() {
                                var drilldown = this.drilldown;
                                if (drilldown) { // drill down
                                    setChart(drilldown.name, drilldown.categories, drilldown.data, drilldown.color);
                                } else { // restore
                                    setChart(name, categories, data,null);
                                }
                            }
                        }
                    },*/
                    dataLabels: {
                        enabled: true,
                        //color: colors[0],
                        style: {
                            fontWeight: 'bold'
                        },
                        formatter: function() {
                            return this.y;
                        }
                    }
                }
            },
            tooltip: {
                /*formatter: function() {
                    var point = this.point,
                        s = this.x +': <b>'+ this.y +'</b> learners<br/>';
                    if (point.drilldown) {
                        s += 'Click to view '+ point.category +' versions';
                    } else {
                        s += 'Click to return to browser brands';
                    }
                    return s;
                }*/
                formatter: function() {
                    return barChartTooltip(this);
                }
            },
            series: [{
                name: name,
                data: data,
                showInLegend: false
            }],
            exporting: {
                enabled: false
            }
        })
        .highcharts(); // return chart

        var chart_drill_line = $('#'+line_chart_div_id).highcharts({
            chart: {
                type: 'line'
            },
            title: {
                text: 'Browser market share, April, 2011 (Line chart with drilldown)'
            },
            subtitle: {
                text: 'Click the columns to view versions. Click again to view brands.'
            },
            xAxis: {
                categories: categories
            },
            yAxis: {
                title: {
                    text: 'Total percent market share'
                }/*,
                labels:{
                  style: {
                    	color: 'green',
                    	fontWeight: 'bold'
                  }
                }*/
            },

            plotOptions: {
                series: {
                    cursor: 'pointer',
                    /*point: {
                        events: {
                            click: function() {
                                var drilldown = this.drilldown;
                                if (drilldown) { // drill down
                                    setLine(drilldown.name, drilldown.categories, drilldown.data, drilldown.color);
                                } else { // restore
                                    setLine(name, categories, data,null);
                                }
                            }
                        }
                    },*/
                    dataLabels: {
                        enabled: true,
                        //color: colors[0],
                        style: {
                            fontWeight: 'bold'
                        }/*,
                        formatter: function() {
                            return this.y +'%';
                        }*/
                    }
                }
            },
            tooltip: {
                formatter: function() {
                    return barChartTooltip(this);
                }
                /*formatter: function() {

                    var point = this.point, s = '<b>'+this.x +' : </b>'+ this.y +' learners<br/>';

                    if (point.drilldown) {
                        s += 'Click to view classifications';
                    } else {
                        s += 'Click to return back';
                    }
                    return s;
                }*/
            },
            series: [{
                name: name,
                data: data,
                color: line_chart_color,
                showInLegend: false
            }],
            exporting: {
                enabled: false
            }
        })
        .highcharts(); // return chart

/*function setLine(name, categories, data, color) {
			chart_drill_line.xAxis[0].setCategories(categories, false);
			chart_drill_line.series[0].remove(false);
			chart_drill_line.addSeries({
				name: name,
				data: data,
				color: color || 'purple'
			}, false);
			chart_drill_line.redraw();
        }*/



        var data_drill_pie = [{
                name : 'MS IE',
                y: 55,
                color: colors[0],
                drilldown: {
                    name: 'MS IE Versions',
                    data: [['IE 6.0',10], ['IE 7.0',7], ['IE 8.0',33], ['IE 9.0',2]],
                    color: colors[0]
                }}, {
               name : 'Firefox',
                y: 25,
                color: colors[1],
                drilldown: {
                    name: 'Firefox versions',
                    data: [['Firefox 12.2',12], ['Firefox 14.5',99], ['Firefox 18.6',36], ['Firefox 20.1',52]],
                    color: colors[0]
                }}, {
               name : 'Safari',
                y: 45,
                color: colors[2],
                drilldown: {
                    name: 'Safari versions',
                    data: [['Safari 2.5',50], ['Safari 4.3',99]],
                    color: colors[0]
                }
            }];

    /*function setPieChart(name, categories, data, color) {
        drill_pie_chart.xAxis[0].setCategories(categories);
        drill_pie_chart.series[0].remove();
        drill_pie_chart.addSeries({
            name: name,
            data: data,
            color: color || 'purple'
        });
    }*/


    var drill_pie_chart = new Highcharts.Chart({
        chart: {
            renderTo: pie_chart_div_id,
            type: 'pie'
        },
        title: {
            text: 'Browser market shares at a specific website, 2010 (Pie chart with drill down)'
        },
        /*tooltip: {
    	    pointFormat: '{series.name}: <b>{point.percentage} {point.name}%%</b>',
        	percentageDecimals: 1
        },*/
        tooltip: {
            formatter: function() {
                    return barChartTooltip(this);
            }
            /*formatter: function() {
                var point = this.point,
                    s = '<b>'+this.point.name +'</b> : '+ this.y +' learners<br>';
                if (point.drilldown) {
                    s += 'Click to view classifications';
                } else {
                    s += 'Click to return back';
                }
                return s;
            }*/

        },
        /*subtitle: {
            text: null
        },
        */
        plotOptions: {
            pie: {
                cursor: 'pointer',
                /*point: {
                    events: {
                        click: function() {
                            var drilldown = this.drilldown;
                            if (drilldown) { // drill down
                                setPieChart(drilldown.name, drilldown.categories, drilldown.data, drilldown.color);
                            } else { // restore
                                setPieChart(name, null, data_drill_pie);
                            }
                        }
                    }
                },*/
                dataLabels: {
                    enabled: true,
                    //color: colors[0],
                    /*style: {
                        fontWeight: 'bold'
                    },*/
                    formatter: function() {
                        //alert("this =");
                        //console.log(this);
                        //return '<b>'+this.point.name +'</b><br>' + this.y + ' learners';
                        return pie_chart_label_format(this);
                    }
                }
            }
        },

        series: [{
            name: name,
            data: data_drill_pie//,
            //color: 'greeen'
        }],
        exporting: {
            enabled: false
        }
    });


$('#'+speedo_chart_div_id).highcharts({

	    chart: {
	        type: 'gauge',
	        plotBackgroundColor: null,
	        plotBackgroundImage: null,
	        plotBorderWidth: 0,
	        plotShadow: false
	    },

	    title: {
	        text: 'Achievers'
	    },

	    pane: {
	        startAngle: -150,
	        endAngle: 150,
	        background: [{
	            backgroundColor: {
	                linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
	                stops: [
	                    [0, '#FFF'],
	                    [1, '#333']
	                ]
	            },
	            borderWidth: 0,
	            outerRadius: '109%'
	        }, {
	            backgroundColor: {
	                linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
	                stops: [
	                    [0, '#333'],
	                    [1, '#FFF']
	                ]
	            },
	            borderWidth: 1,
	            outerRadius: '107%'
	        }, {
	            // default background
	        }, {
	            backgroundColor: '#DDD',
	            borderWidth: 0,
	            outerRadius: '105%',
	            innerRadius: '103%'
	        }]
	    },

	    // the value axis
	    yAxis: {
	        min: 0,
	        max: 100,

	        minorTickInterval: 'auto',
	        minorTickWidth: 1,
	        minorTickLength: 10,
	        minorTickPosition: 'inside',
	        minorTickColor: '#666',

	        tickPixelInterval: 30,
	        tickWidth: 2,
	        tickPosition: 'inside',
	        tickLength: 10,
	        tickColor: '#666',
	        labels: {
	            step: 2,
	            rotation: 'auto'
	        }//,
	        /*title: {
	            text: 'km/h'
	        },
	        plotBands: [{
	            from: 0,
	            to: 120,
	            color: '#55BF3B' // green
	        }, {
	            from: 120,
	            to: 160,
	            color: '#DDDF0D' // yellow
	        }, {
	            from: 160,
	            to: 200,
	            color: '#DF5353' // red
	        }]*/
	    },

	    series: [{
	        name: 'Achievers',
	        data: [55],
	        tooltip: {
	           // valueSuffix: ' km/h'
	        }
	    }],
        exporting: {
            enabled: false
        }
	});


});

<?php
if($report_type == "sla_kpi_rep_retention" || $report_type == 'sla_kpi_rep_overall_success' || $report_type == 'sla_kpi_rep_timely_success')
{
?>
    var pie_chart_data_label_format = "<b>dl_point_name<b><br>dl_point_value %";
<?php
}
else
{
?>
    var pie_chart_data_label_format = "<b>dl_point_name<b><br>dl_point_value learners";
<?php
}
?>
function pie_chart_label_format(obj)
{
    var pie_chart_data_label_frmat = pie_chart_data_label_format;
    pie_chart_data_label_frmat = pie_chart_data_label_frmat.replace("dl_point_name",obj.point.name);
    pie_chart_data_label_frmat = pie_chart_data_label_frmat.replace("dl_point_value",obj.y);

    return pie_chart_data_label_frmat;
}

function redirectPage(value)
{
    window.parent.location.href="do.php?_action=sla_kpi_rep_achievers&report_type="+value;
}

function redirectPageForReport()
{
    //window.parent.location.href="do.php?_action="+report_type+"&page_mode=generate_report&"+$('#report_criteria_form').serialize();
    if(report_type == "sla_kpi_rep_achievers")
    {
        window.parent.location.href="do.php?_action=sla_kpi_generate_report&page_mode=generate_report&"+$('#report_criteria_form').serialize()+"&report_type="+report_type;
    }
    else if(report_type == "sla_kpi_rep_last_visit")
    {
        window.parent.location.href="do.php?_action=sla_kpi_generate_report_last_visit&page_mode=generate_report&"+$('#report_criteria_form').serialize()+"&report_type="+report_type;
    }
    else if(report_type == "sla_kpi_rep_new_starts")
    {
        window.parent.location.href="do.php?_action=sla_kpi_generate_report_new_starts&page_mode=generate_report&"+$('#report_criteria_form').serialize()+"&report_type="+report_type;
    }
    else if(report_type == "sla_kpi_rep_completions")
    {
        window.parent.location.href="do.php?_action=sla_kpi_generate_report_completions&page_mode=generate_report&"+$('#report_criteria_form').serialize()+"&report_type="+report_type;
    }
    else if(report_type == "sla_kpi_rep_early_leavers")
    {
        window.parent.location.href="do.php?_action=sla_kpi_generate_report_early_leavers&page_mode=generate_report&"+$('#report_criteria_form').serialize()+"&report_type="+report_type;
    }
    else if(report_type == "sla_kpi_rep_learners")
    {
        window.parent.location.href="do.php?_action=sla_kpi_generate_report_learners&page_mode=generate_report&"+$('#report_criteria_form').serialize()+"&report_type="+report_type;
    }
    else if(report_type == "sla_kpi_rep_retention")
    {
        //alert($('#report_criteria_form').serialize());
        window.parent.location.href="do.php?_action=sla_kpi_generate_report_retention&page_mode=generate_report&"+$('#report_criteria_form').serialize()+"&report_type="+report_type;
    }
    else if(report_type == "sla_kpi_rep_overall_success")
    {
        //alert($('#report_criteria_form').serialize());
        window.parent.location.href="do.php?_action=sla_kpi_generate_report_overall_success&report=overall_success&page_mode=generate_report&"+$('#report_criteria_form').serialize()+"&report_type="+report_type;
    }
    else if(report_type == "sla_kpi_rep_timely_success")
    {
        //alert($('#report_criteria_form').serialize());
        window.parent.location.href="do.php?_action=sla_kpi_generate_report_overall_success&report=timely_success&page_mode=generate_report&"+$('#report_criteria_form').serialize()+"&report_type="+report_type;
    }
    else if(report_type == "sla_kpi_rep_progression")
    {
        //alert($('#report_criteria_form').serialize());
        window.parent.location.href="do.php?_action=sla_kpi_generate_report_progression&page_mode=generate_report&"+$('#report_criteria_form').serialize()+"&report_type="+report_type;
    }
    else if(report_type == "sla_kpi_rep_progression_l2tol3")
    {
        //alert($('#report_criteria_form').serialize());
        window.parent.location.href="do.php?_action=sla_kpi_generate_report_progression&page_mode=generate_report&show_only=l2tol3&"+$('#report_criteria_form').serialize()+"&report_type="+report_type;
    }
}

function show_hide_graph_types(graph_type)
{
    if(data_exists !="false")
    {
        var div_line_graph = document.getElementById("container_line");
        var div_pie_graph = document.getElementById("container_pie");
        var div_bar_graph = document.getElementById("container");

        if(graph_type == "line")
        {
            $('#div_two_graphs').fadeOut();/*$(div_pie_graph).fadeOut();$(div_bar_graph).fadeOut();*/
        }
        else if(graph_type == "line_bar")
        {
            $('#div_two_graphs').fadeIn();
            $(div_bar_graph).fadeIn();
            $(div_pie_graph).fadeOut();
        }
        else if(graph_type == "line_bar_pie")
        {
            $('#div_two_graphs').fadeIn();
            $(div_bar_graph).fadeIn();
            $(div_pie_graph).fadeIn();
        }
    }
}

function save_filters()
{
//alert('function save_filters');
    $.ajax({
        type:"GET",
        data:"save_filters=save_filters&report_type="+report_type+"&"+$('#report_criteria_form').serialize(),
        url:"do.php?_action=ajax_sla_kpi_reports",
        dataType : 'json',
        beforeSend:function(data)
        {
            //alert('before send');
            $('#sp_saved_filters').hide();
            $('#sp_saving_filters').show();
        },
        success:function(response)
        {
            $('#sp_saving_filters').fadeOut();
            $('#sp_saved_filters').fadeIn();
        }
   });
}
</script>

<style type="text/css">
.lbl
{
  font-family:'Arial'; color:#666666; font-size:13px;
}
.div_flds
{
    margin: 5px 0 0 0;
}


</style>
</head>

<body>
<!--<div class="banner">
	<div class="Title">
        <?php echo $page_title;?>
    </div>
</div>-->

<div style="margin:0 0 0 7px;">
    <font style="color: #395596;font-size: 11pt; font-weight: bold; float: left;"><?php echo $page_title;?></font>
    <a href="javascript:redirectPageForReport();" style="float: right;">View report</a>
<!--Select :
<select name="report_type" id="report_type" onchange="redirectPage(this.value)">
<option value="sla_kpi_rep_progression" <?php if($report_type == "sla_kpi_rep_progression"){echo 'selected';}?>>Onward progression – L2, L3, L4</option>
<option value="sla_kpi_rep_achievers" <?php if($report_type == "sla_kpi_rep_achievers"){echo 'selected';}?>>Progress through qualification, achieved to date</option>
<option value="sla_kpi_rep_retention" <?php if($report_type == "sla_kpi_rep_retention"){echo 'selected';}?>>Retention Rates over time</option>
<option value="sla_kpi_rep_overall_success" <?php if($report_type == "sla_kpi_rep_overall_success"){echo 'selected';}?>>Overall Success Rates</option>
<option value="sla_kpi_rep_timely_success" <?php if($report_type == "sla_kpi_rep_timely_success"){echo 'selected';}?>>Timely Success Rates</option>
<option value="sla_kpi_rep_last_visit" <?php if($report_type == "sla_kpi_rep_last_visit"){echo 'selected';}?>>Weeks since last visit for each learner</option>
<option value="sla_kpi_rep_new_starts" <?php if($report_type == "sla_kpi_rep_new_starts"){echo 'selected';}?>>Number of starts in period and over time</option>
<option value="sla_kpi_rep_completions" <?php if($report_type == "sla_kpi_rep_completions"){echo 'selected';}?>>Number of completions in period and over time</option>
<option value="sla_kpi_rep_early_leavers" <?php if($report_type == "sla_kpi_rep_early_leavers"){echo 'selected';}?>>Number of early leavers in period and over time</option>

<option value="sla_kpi_rep_learners" <?php if($report_type == "sla_kpi_rep_learners"){echo 'selected';}?>>Number of Learners broken down by criterias</option>
</select>-->
</div>

<br style="clear: both;"/><br style="clear: both;"/>

<!--<table style="left: 10px; width: 1233px; border: 1px solid #ABABAB; border-collapse: collapse; margin:0 0 0 7px; background-color: #EFEFEF;" cellpadding="6" border="0">-->



<form name="report_criteria_form" id="report_criteria_form">

<input type="hidden" name="drill_down_by" id="drill_down_by" value="<?php echo $filter_arr->drill_down_by;?>">



<?php

if($report_type == 'sla_kpi_rep_last_visit' || $report_type == 'sla_kpi_rep_new_starts' || $report_type == 'sla_kpi_rep_completions' || $report_type == 'sla_kpi_rep_early_leavers' || $report_type == "sla_kpi_rep_achievers" || $report_type == 'sla_kpi_rep_learners' || $report_type == 'sla_kpi_rep_retention' || $report_type == 'sla_kpi_rep_overall_success' || $report_type == 'sla_kpi_rep_timely_success' || $report_type == 'sla_kpi_rep_progression' || $report_type == 'sla_kpi_rep_progression_l2tol3')
{
?>
    <input type="hidden" name="assessor" id="assessor" value="<?php echo $filter_arr->assessor;?>">
<?php
}
if($report_type == 'sla_kpi_rep_last_visit' || $report_type == 'sla_kpi_rep_new_starts' || $report_type == 'sla_kpi_rep_completions' || $report_type == 'sla_kpi_rep_early_leavers' || $report_type == "sla_kpi_rep_achievers" || $report_type == 'sla_kpi_rep_learners' || $report_type == 'sla_kpi_rep_retention' || $report_type == 'sla_kpi_rep_overall_success' || $report_type == 'sla_kpi_rep_timely_success' || $report_type == 'sla_kpi_rep_progression' || $report_type == 'sla_kpi_rep_progression_l2tol3')
{
?>
    <input type="hidden" name="contract" id="contract" value="<?php echo $filter_arr->contract;?>">
<?php
}

if($report_type == 'sla_kpi_rep_new_starts' || $report_type == 'sla_kpi_rep_completions' || $report_type == "sla_kpi_rep_achievers" || $report_type == 'sla_kpi_rep_early_leavers' || $report_type == 'sla_kpi_rep_last_visit' || $report_type == 'sla_kpi_rep_learners' || $report_type == 'sla_kpi_rep_retention' || $report_type == 'sla_kpi_rep_overall_success' || $report_type == 'sla_kpi_rep_timely_success' || $report_type == 'sla_kpi_rep_progression' || $report_type == 'sla_kpi_rep_progression_l2tol3')
{
?>
    <input type="hidden" name="employer" id="employer" value="<?php echo $filter_arr->employer;?>">
<?php
}

if($report_type == 'sla_kpi_rep_new_starts' || $report_type == 'sla_kpi_rep_completions' || $report_type == 'sla_kpi_rep_early_leavers' || $report_type == "sla_kpi_rep_achievers" || $report_type == 'sla_kpi_rep_last_visit' || $report_type == 'sla_kpi_rep_learners' || $report_type == 'sla_kpi_rep_retention' || $report_type == 'sla_kpi_rep_overall_success' || $report_type == 'sla_kpi_rep_timely_success' || $report_type == 'sla_kpi_rep_progression' || $report_type == 'sla_kpi_rep_progression_l2tol3')
{
?>
    <input type="hidden" name="training_provider" id="training_provider" value="<?php echo $filter_arr->training_provider;?>">
<?php
}

if($report_type == 'sla_kpi_rep_learners')
{
?>
    <input type="hidden" name="progress" id="progress" value="<?php echo $filter_arr->progress;?>">
    <input type="hidden" name="record_status" id="record_status" value="<?php echo $filter_arr->record_status;?>">
    <input type="hidden" name="programme" id="programme" value="<?php echo $filter_arr->programme;?>">
    <input type="hidden" name="group" id="group" value="<?php echo $filter_arr->group;?>">
<?php
}
if($report_type == 'sla_kpi_rep_learners' || $report_type == 'sla_kpi_rep_retention' || $report_type == 'sla_kpi_rep_progression' || $report_type == 'sla_kpi_rep_progression_l2tol3')
{
?>
    <input type="hidden" name="gender" id="gender" value="<?php echo $filter_arr->gender;?>">
    <input type="hidden" name="course" id="course" value="<?php echo $filter_arr->course;?>">
    <input type="hidden" name="framework" id="framework" value="<?php echo $filter_arr->framework;?>">
<?php
}

if($report_type == 'sla_kpi_rep_retention')
{
?>
    <input type="hidden" name="valid" id="valid" value="<?php echo $filter_arr->valid;?>">
    <input type="hidden" name="active" id="active" value="<?php echo $filter_arr->active;?>">
    <input type="hidden" name="submission" id="submission" value="<?php echo $filter_arr->submission;?>">
    <input type="hidden" name="contract_year" id="contract_year" value="<?php echo $filter_arr->contract_year;?>">
<?php
}


if($report_type == 'sla_kpi_rep_overall_success' || $report_type == 'sla_kpi_rep_timely_success')
{
    if($report_type == 'sla_kpi_rep_overall_success')
    {
        $report = "overall_success";
    }
    else if($report_type == 'sla_kpi_rep_timely_success')
    {
        $report = "timely_success";
    }
?>
    <input type="hidden" name="report" value="<?php echo $report; ?>">

    <input type="hidden" name="age_band" id="age_band" value="<?php echo $filter_arr->age_band;?>">
    <input type="hidden" name="programme_type" id="programme_type" value="<?php echo $filter_arr->programme_type;?>">
    <input type="hidden" name="ssa" id="ssa" value="<?php echo $filter_arr->ssa;?>">
    <input type="hidden" name="ethnicity" id="ethnicity" value="<?php echo $filter_arr->ethnicity;?>">
<?php
}

if($report_type == 'sla_kpi_rep_progression' || $report_type == 'sla_kpi_rep_progression_l2tol3')
{
?>
    <input type="hidden" name="ethnicity" id="ethnicity" value="<?php echo $filter_arr->ethnicity;?>">
    <input type="hidden" name="submission" id="submission" value="<?php echo $filter_arr->submission;?>">
    <input type="hidden" name="contract_year" id="contract_year" value="<?php echo $filter_arr->contract_year;?>">
<?php
}

if($report_type == 'sla_kpi_rep_learners')
{
?>
    <input type="hidden" name="from_date" id="from_date" value="<?php echo empty($_REQUEST['from_date'])? $filter_arr->from_date : $_REQUEST['from_date']?>">
    <input type="hidden" name="to_date" id="to_date" value="<?php echo empty($_REQUEST['to_date'])? $filter_arr->to_date : $_REQUEST['to_date']?>">
    <input type="hidden" name="target_start_date" id="target_start_date" value="<?php echo empty($_REQUEST['target_start_date'])? $filter_arr->target_start_date : $_REQUEST['target_start_date']?>">
    <input type="hidden" name="target_end_date" id="target_end_date" value="<?php echo empty($_REQUEST['target_end_date'])? $filter_arr->target_end_date : $_REQUEST['target_end_date']?>">
    <input type="hidden" name="closure_start_date" id="closure_start_date" value="<?php echo empty($_REQUEST['closure_start_date'])? $filter_arr->closure_start_date : $_REQUEST['closure_start_date']?>">
    <input type="hidden" name="closure_end_date" id="closure_end_date" value="<?php echo empty($_REQUEST['closure_end_date'])? $filter_arr->closure_end_date : $_REQUEST['closure_end_date']?>">

<?php
}

if($report_type == 'sla_kpi_rep_achievers')
{
?>
    <input type="hidden" name="from_date" id="from_date" value="<?php echo empty($_REQUEST['from_date'])? $filter_arr->from_date : $_REQUEST['from_date']?>">
    <input type="hidden" name="to_date" id="to_date" value="<?php echo empty($_REQUEST['to_date'])? $filter_arr->to_date : $_REQUEST['to_date']?>">
<?php
}

if($report_type == 'sla_kpi_rep_last_visit')
{
?>
    <input type="hidden" name="from_date" id="from_date" value="<?php echo empty($_REQUEST['from_date'])? $filter_arr->from_date : $_REQUEST['from_date']?>">
    <input type="hidden" name="to_date" id="to_date" value="<?php echo empty($_REQUEST['to_date'])? $filter_arr->to_date : $_REQUEST['to_date']?>">
<?php
}

if($report_type == 'sla_kpi_rep_new_starts' || $report_type == 'sla_kpi_rep_completions' || $report_type == 'sla_kpi_rep_early_leavers')
{
?>
    <input type="hidden" name="from_date" id="from_date" value="<?php echo empty($_REQUEST['from_date'])? $filter_arr->from_date : $_REQUEST['from_date']?>">
    <input type="hidden" name="to_date" id="to_date" value="<?php echo empty($_REQUEST['to_date'])? $filter_arr->to_date : $_REQUEST['to_date']?>">
<?php
}
?>


    <!--<br>

    <input type="button" id="go_button" name="go_button" value="Go" onclick="javascript:load_graphs()"/>
    &nbsp;&nbsp;
    <input type="button" name="btn_save_filters" id="btn_save_filters" value="Save filters" onclick="javascript:save_filters()" />
    &nbsp;&nbsp;-->


    <!--<br>

    <span id="sp_saving_filters" style="display: none;">
    &nbsp;&nbsp;
        <img src="/images/wait30.gif" />
        <b>Saving filter, please wait....</b>
    </span>

    <span id="sp_saved_filters" style="display: none; margin: 0 0 0 5px;">
        <br>
        <b>Filter has been saved !</b>
    </span>-->

    <span id="sp_loading_graphs">
    &nbsp;&nbsp;
        <img src="/images/wait30.gif" />
        <b>Loading graphs, please wait....</b>
    </span>

    <div id="div_no_data_found" style="margin:0 0 0 7px; display: none;">
        <font style="font-weight: bold;">Sorry, no data found !</font>
    </div>

<div style="clear: both"></div>

<div id="upper_div" style="display: none;width: 1241px;">
    <!-- Data table div -->
    <div id="div_data_table" style="left: 10px; width: 1233px; margin:0 0 0 7px; width: 912px; float: left; display: none;"></div>

    <!-- Speedo chart  -->
    <div id="container_speedo" style="width: 300px; height: 200px; float: left; display: none;"></div>
</div>



<!--<div style="width: 1241px;">-->

<div style="width: 930px; float: left;">
    <div style="position: absolute; left: 10px; width: 1160px;" id="u211">
        <!-- Line chart -->
        <div style="left:10px; top:10px; width:1110px; height:381px; overflow:visible; border: 1px solid #ABABAB; border-radius: 7px 7px 7px 7px; display:none;" id="container_line"></div>


        <!--<div style="clear: both;"></div><br><br>-->


        <div id="div_two_graphs" style="float: left; width:1154px; margin: 0px 0 0; border: 1px solid #ABABAB; border-radius: 7px 7px 7px 7px; <?php if($page_mode == "generate_report" || $filter_arr->graph_type=='line'){echo 'display:none;';} ?>">

            <!-- Pie chart -->
            <div id="container_pie" style="width:1110px; height:381px;float:left; <?php /*if($filter_arr->graph_type=='line_bar')*/{echo 'display:none;';} ?>"></div>

            <!-- Bar chart -->
            <div id="container" style="width:1110px; height:381px;float:left; margin:20px; 0 0 0;clear: both; <?php if($filter_arr->graph_type=='line'){echo 'display:none;';} ?>"></div>
        </div>

    </div>
</div>

<!--</div>-->
</form>

<script type="text/javascript">
//<![CDATA[
var ele = document.getElementById("from_date");
//ele.resetToDefault = function(){this.value = "24/06/2013"};
ele.validate = function(){
if(this.value && window.stringToDate && !window.stringToDate(this.value)){
alert("Invalid calendar-date or invalid date-format. Please use the format dd-mm-yyyy.");
this.value = "";
this.focus();
return false;
}
return true;
}
//]]>

//<![CDATA[
var ele = document.getElementById("to_date");
//ele.resetToDefault = function(){this.value = "24/06/2013"};
ele.validate = function(){
if(this.value && window.stringToDate && !window.stringToDate(this.value)){
alert("Invalid calendar-date or invalid date-format. Please use the format dd-mm-yyyy.");
this.value = "";
this.focus();
return false;
}
return true;
}
//]]>

<?php
if($report_type == "sla_kpi_rep_learners")
{
?>
//<![CDATA[
var ele = document.getElementById("target_start_date");
//ele.resetToDefault = function(){this.value = "24/06/2013"};
ele.validate = function(){
if(this.value && window.stringToDate && !window.stringToDate(this.value)){
alert("Invalid calendar-date or invalid date-format. Please use the format dd-mm-yyyy.");
this.value = "";
this.focus();
return false;
}
return true;
}
//]]>

//<![CDATA[
var ele = document.getElementById("target_end_date");
//ele.resetToDefault = function(){this.value = "24/06/2013"};
ele.validate = function(){
if(this.value && window.stringToDate && !window.stringToDate(this.value)){
alert("Invalid calendar-date or invalid date-format. Please use the format dd-mm-yyyy.");
this.value = "";
this.focus();
return false;
}
return true;
}
//]]>

//<![CDATA[
var ele = document.getElementById("closure_start_date");
//ele.resetToDefault = function(){this.value = "24/06/2013"};
ele.validate = function(){
if(this.value && window.stringToDate && !window.stringToDate(this.value)){
alert("Invalid calendar-date or invalid date-format. Please use the format dd-mm-yyyy.");
this.value = "";
this.focus();
return false;
}
return true;
}
//]]>

//<![CDATA[
var ele = document.getElementById("closure_end_date");
//ele.resetToDefault = function(){this.value = "24/06/2013"};
ele.validate = function(){
if(this.value && window.stringToDate && !window.stringToDate(this.value)){
alert("Invalid calendar-date or invalid date-format. Please use the format dd-mm-yyyy.");
this.value = "";
this.focus();
return false;
}
return true;
}
//]]>

//<![CDATA[
var ele = document.getElementById("work_experience_start_date");
//ele.resetToDefault = function(){this.value = "24/06/2013"};
ele.validate = function(){
if(this.value && window.stringToDate && !window.stringToDate(this.value)){
alert("Invalid calendar-date or invalid date-format. Please use the format dd-mm-yyyy.");
this.value = "";
this.focus();
return false;
}
return true;
}
//]]>

//<![CDATA[
var ele = document.getElementById("work_experience_end_date");
//ele.resetToDefault = function(){this.value = "24/06/2013"};
ele.validate = function(){
if(this.value && window.stringToDate && !window.stringToDate(this.value)){
alert("Invalid calendar-date or invalid date-format. Please use the format dd-mm-yyyy.");
this.value = "";
this.focus();
return false;
}
return true;
}
//]]>
<?php
}
?>
</script>
<!-- Popup calendar -->
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>
</body>
</html>