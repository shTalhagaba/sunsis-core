<?php

require "./rf/razorflow.php";
Dashboard::setTitle("Sales Dashboard");
/*
require_once("./rf/core/internal/RFOptions.php");
require_once("./rf/core/lib/Dashboard.php");
require_once("./rf/core/internal/RFRPC.php");
require_once("./rf/core/internal/RFDevTools.php");
require_once("./rf/core/internal/RFLog.php");
require_once("./rf/core/internal/RFConfig.php");
require_once("./rf/core/internal/RFDbDataSource.php");
require_once("./rf/core/lib/DataSources.php");
*/
$dataSource = new MySQLDataSource('ams', isset($_SERVER['PERSPECTIVE_DB_USER'])?$_SERVER['PERSPECTIVE_DB_USER']:ini_get('mysqli.default_user'), isset($_SERVER['PERSPECTIVE_DB_PASSWORD'])?$_SERVER['PERSPECTIVE_DB_PASSWORD']:ini_get('mysqli.default_pw'), isset($_SERVER['PERSPECTIVE_DB_HOST'])?$_SERVER['PERSPECTIVE_DB_HOST']:ini_get('mysqli.default_host'));
$dataSource->setSQLSource('users left join tr on users.username = tr.assessor where users.type = 3');

Dashboard::setTitle("Learners by course");

$dataSource2 = new SQLiteDataSource("./rf/demos/databases/northwind.sqlite");
$dataSource2->setSQLSource("Products JOIN categories ON categories.CategoryID = Products.CategoryID");

$categoryList = array(
    'Beverages' => 1,
    'Condiments' => 2,
    'Confections' => 3,
    'Diary Products' => 4,
    'Grains/Cereals' => 5,
    'Meat/Poultry' => 6,
    'Produce' => 7,
    'Seafood' => 8
);

/*foreach ($categoryList as $categoryName => $categoryID) {
    $catKPI = new KPIComponent();
    $catKPI->setCaption("$categoryName");
    $catKPI->setDataSource($dataSource);
    $catKPI->setValueExpression("UnitsInStock", array(
        'aggregate' => true,
        'aggregateFunction' => "SUM",
        'numberSuffix' => " units"
    ));
    $catKPI->addCondition("Products.CategoryID", "=", $categoryID);
    Dashboard::addComponent($catKPI);
}
*/

$distributionOfStock = new ChartComponent();
$distributionOfStock->setCaption("Distribution of learners by courses");
$distributionOfStock->setDataSource($dataSource);
$distributionOfStock->setYAxis("Quantity", array('numberSuffix' => " learners"));
$distributionOfStock->setLabelExpression("Category Name", "CONCAT(users.firstnames,' ',users.surname)");
$distributionOfStock->addSeries("Learners", "IF(tr.assessor=users.username,1,0)", array(
    'displayType' => "Pie",
));
Dashboard::addComponent($distributionOfStock);

$distributionOfRevenue = new ChartComponent();
$distributionOfRevenue->setCaption("Distribution of inventory by value");
$distributionOfRevenue->setYAxis("Revenue", array('numberPrefix' => "$"));
$distributionOfRevenue->setDataSource($dataSource2);
$distributionOfRevenue->setLabelExpression("Category Name", "CategoryName");
$distributionOfRevenue->addSeries("Stock Quantity", "UnitsInStock * UnitPrice", array(
    'displayType' => "Pie",
));
Dashboard::addComponent($distributionOfRevenue);

$productList = new TableComponent();
$productList->setCaption("Learners");
$productList->setDataSource($dataSource);
$productList->setDimensions(2, 2);
$productList->addColumn("Firstnames", "tr.firstnames");
$productList->addColumn("Surname", "tr.surname");
//$productList->addColumn("Price", "UnitPrice", array('numberPrefix' => "$"));
//$productList->addColumn("Units in Stock", "UnitsInStock");
//$productList->addColumn("Units in Order", "UnitsOnOrder");
//$productList->addColumn("Reorder Level", "ReorderLevel");
//Dashboard::addComponent($productList);

$productFilter = new ConditionFilterComponent();
$productFilter->setCaption("Filter items in stock");
$productFilter->setDataSource($dataSource);
$productFilter->setDimensions(2, 2);
$categoryNames = array(); $categoryConditions = array();
foreach($categoryList as $categoryName => $categoryID) {
    $categoryNames []= $categoryName;
    $categoryConditions []= "Products.CategoryID = $categoryID";
}
$productFilter->addSelectcondition("Select Category", $categoryNames, $categoryConditions);
$productFilter->addTextContainsCondition("Product Name Contains", "ProductName LIKE {{value}}");
$productFilter->addCheckboxCondition("Low Stock", "UnitsInStock < ReorderLevel");
$productFilter->addCheckboxCondition("Exclude Discontinued Items", "Discontinued = 0");
//Dashboard::addComponent($productFilter);

$productFilter->addFilterTo($productList);

Dashboard::Render();



?>