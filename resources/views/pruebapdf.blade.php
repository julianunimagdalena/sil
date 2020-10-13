<!DOCTYPE html>
<html ng-app="dipro">
<head>
	<title>PDF</title>
	<meta charset="utf-8">
</head>
<body ng-controller="AdminIndicadoresCtrl">
	<h1>Grafico</h1>
	<div id="generos-chart"></div>
	<div id="a"></div>

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="{{asset('/js/angular.min.js')}}" type="text/javascript"></script>
    <script type="text/javascript">
    	document.getElementById('a').innerHTML = 'asd';
    	var dipro = angular.module('dipro', []);
    	dipro.controller('AdminIndicadoresCtrl', ['$scope', '$http', function($scope, $http){
    		google.charts.load('current', { 'packages': ['corechart'] });
		    google.charts.setOnLoadCallback(function () {
		        $http.get('http://localhost/sil/public/' + '/adminsil/datos-indicadores').then(function (response) {
		            $scope.datos = response.data;

		            var data = new google.visualization.DataTable();
		            data.addColumn('string', 'Genero');
		            data.addColumn('number', 'Numero');
		            data.addRows([
		                ['Masculino', $scope.datos.graduadosMasculinos],
		                ['Femenino', $scope.datos.graduadosFemeninos],
		            ]);

		            var options = {
		                'backgroundColor': '#FAFAFA',
		                width: '100%',
		                height: '300'
		            };

		            var chart = new google.visualization.PieChart(document.querySelector('#generos-chart'));
		            chart.draw(data, options);
		        });
		    });
    	}]);
    </script>

</body>
</html>