$(function(){

	$('table.MonthNavigation td.DayValue').click(function(){
		if($(this).text()){
			var dayAndYear = $('table.MonthNavigation td.MonthLabel').text();
			var d = new Date($(this).text() + ' ' + dayAndYear);
			d = d.getFullYear() + "-" + (d.getMonth()+1) + "-" + d.getDate();
			d = d + '|' + d;
			//window.location.href="?_action=" + $('form[name=filters] input[name=_action]').val() + "&ViewSessionsRegisters_filter_date=" + encodeURIComponent(d);
			window.location.href="?_action=" + $('form[name=filters] input[name=_action]').val() + "&" + window.phpView + "filter_date=" + encodeURIComponent(d);
		}
	});

	$('table.MonthNavigation td.DayValue').mouseover(function(){
		$(this).addClass("DayValueHover");
	});

	$('table.MonthNavigation td.DayValue').mouseout(function(){
		$(this).removeClass("DayValueHover");
	});

	$('table.MonthNavigation td.NavigationPrevious').mouseover(function(){
		$(this).addClass("NavHover");
	});

	$('table.MonthNavigation td.NavigationPrevious').mouseout(function(){
		$(this).removeClass("NavHover");
	});

	$('table.MonthNavigation td.NavigationNext').mouseover(function(){
		$(this).addClass("NavHover");
	});

	$('table.MonthNavigation td.NavigationNext').mouseout(function(){
		$(this).removeClass("NavHover");
	});

	$('table.MonthNavigation td.NavigationPrevious').click(calendarPreviousMonth);
	$('table.MonthNavigation td.NavigationNext').click(calendarNextMonth);


	$('table.MonthNavigation td.DayLabel').mouseover(function(){
		$(this).addClass("DayLabelHover");
		var $dayValues = $('table.MonthNavigation td.DayValue');
		var $cell = $(this);
		do {
			$dayValues.eq($cell[0].cellIndex - 1).addClass("DayValueHover");
			$cell = $cell.prev("td.DayLabel");
		} while($cell.length && $cell.text() != "Sa");

		var $cell = $(this);
		do {
			$dayValues.eq($cell[0].cellIndex - 1).addClass("DayValueHover");
			$cell = $cell.next("td.DayLabel");
		} while($cell.length && $cell.text() != "Su" && $cell.text() != "");
	});


	$('table.MonthNavigation td.DayLabel').mouseout(function(){
		$(this).removeClass("DayLabelHover");
		var $dayValues = $('table.MonthNavigation td.DayValue');
		var $cell = $(this);
		do {
			$dayValues.eq($cell[0].cellIndex - 1).removeClass("DayValueHover");
			$cell = $cell.prev("td.DayLabel");
		} while($cell.length && $cell.text() != "Sa");

		var $cell = $(this);
		do {
			$dayValues.eq($cell[0].cellIndex - 1).removeClass("DayValueHover");
			$cell = $cell.next("td.DayLabel");
		} while($cell.length && $cell.text() != "Su" && $cell.text() != "");
	});


	$('table.MonthNavigation td.DayLabel').click(selectWeek);

});

function calendarPreviousMonth(e)
{
	var monthAndYear = $('table.MonthNavigation td.MonthLabel').text();
	var ts = Date.parse("1 " + monthAndYear);
	var endDate = new Date(ts - 1000); // The day before

	redrawCalendar(new Date(endDate.getFullYear(), endDate.getMonth(), 1));
}

function calendarNextMonth(e)
{
	var monthAndYear = $('table.MonthNavigation td.MonthLabel').text();
	var ts = Date.parse("1 " + monthAndYear);
	var d = new Date(ts);

	if(d.getMonth() == 11)
	{
		redrawCalendar(new Date(d.getFullYear() + 1, 0, 1));
	}
	else
	{
		redrawCalendar(new Date(d.getFullYear(), d.getMonth()+1, 1));
	}
}


function selectWeek(e)
{
	if($(this).text() == ""){
		return;
	}

	var dayOfMonth = this.cellIndex;
	var monthAndYear = $('table.MonthNavigation td.MonthLabel').text();
	var d = new Date (dayOfMonth + " " + monthAndYear);

	var ts = d.getTime();
	var dayMs = 60*60*24*1000;
	var startTs = ts - (d.getDay() * dayMs);
	var endTs = ts + ( (6 - d.getDay()) * dayMs);
	var startDate = new Date(startTs);
	var endDate = new Date(endTs);

	var dateString = startDate.getFullYear() + "-" + (startDate.getMonth()+1) + "-" + startDate.getDate() + "|"
		+ endDate.getFullYear() + "-" + (endDate.getMonth()+1) + "-" + endDate.getDate();

	//window.location.href="?_action=" + $('form[name=filters] input[name=_action]').val() + "&ViewSessionsRegisters_filter_date=" + encodeURIComponent(dateString);
	window.location.href="?_action=" + $('form[name=filters] input[name=_action]').val() + "&" + window.phpView + "filter_date=" + encodeURIComponent(dateString);
}


function redrawCalendar(date)
{
	var weekdays = ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'];
	var months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
	var dayMs = 1000 * 60 * 60 * 24;

	var month = date.getMonth();
	var year = date.getFullYear();
	var maxDays = cal_days_in_month(month, year);

	var today = new Date();
	var today_date = today.getDate();
	var today_month = today.getMonth();
	var today_year = today.getFullYear();

	var startDate = new Date(stringToDate($('input[name=filter_date_start_date]').val()));
	var startDateDay = startDate.getDate();
	var startDateMonth = startDate.getMonth();
	var startDateYear = startDate.getFullYear();

	var endDate = new Date(stringToDate($('input[name=filter_date_end_date]').val()));
	var endDateDay = endDate.getDate();
	var endDateMonth = endDate.getMonth();
	var endDateYear = endDate.getFullYear();

	var $cells = $('table.MonthNavigation td.DayLabel');
	var d = new Date((month+1) + "/1/" + year);
	for(var i = 0; i < $cells.length; i++)
	{
		if(d.getMonth() != month)
		{
			$cells.eq(i).text("").removeClass("Weekday").removeClass("Weekend");
		}
		else
		{
			$cells.eq(i).text(weekdays[d.getDay()]);
			if(d.getDay() == 0 || d.getDay() == 6)
			{
				$cells.eq(i).text(weekdays[d.getDay()]).removeClass("Weekday").addClass("Weekend");
			}
			else
			{
				$cells.eq(i).text(weekdays[d.getDay()]).removeClass("Weekend").addClass("Weekday");
			}
		}
		d.setTime(d.getTime() + dayMs);
	}

	var day;
	var $cells = $('table.MonthNavigation td.DayValue');
	//var d = new Date((month+1) + "/1/" + year);
	for(var i = 0; i < $cells.length; i++)
	{
		day = i + 1;
		if(day > maxDays)
		{
			$cells.eq(i).html("").removeClass("Today").removeClass("SelectedDay");
		}
		else
		{
			$cells.eq(i).text(day).removeClass("Today").removeClass("SelectedDay");

			if(month == today_month && day == today_date && year == today_year){
				$cells.eq(i).addClass("Today");
			}

			if(dateCmp(day, month, year, startDateDay, startDateMonth, startDateYear) >= 0
				&& dateCmp(day, month, year, endDateDay, endDateMonth, endDateYear) <= 0)
			{
				$cells.eq(i).addClass("SelectedDay");
			}
		}
	}

	$('table.MonthNavigation td.MonthLabel').text(months[month] + " " + year);
}


function cal_days_in_month(month, year)
{
	month++;
	if(month > 11){
		month = 0;
		year++;
	}

	var d = new Date(year, month, 1);
	d.setTime(d.getTime() - 1000);

	return d.getDate();
}


function dateCmp(day1, month1, year1, day2, month2, year2)
{
	if(year1 > year2)
	{
		return 1;
	}
	else if(year1 < year2)
	{
		return -1;
	}

	// Years are equal from hereon
	if(month1 > month2)
	{
		return 1;
	}
	else if(month1 < month2)
	{
		return -1;
	}

	// Months are equal from hereon
	if(day1 > day2)
	{
		return 1;
	}
	else if (day1 < day2)
	{
		return -1
	}

	// All values are equal
	return 0
}