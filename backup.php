<script>
	document.addEventListener('DOMContentLoaded', function () {
        var myChart = Highcharts.chart('container', {
            chart: {
                type: 'bar'
            },

            title: {
                text: 'Средние значения (все данные)'
            },
            	
            xAxis: {
                categories: ['BEELINE_VALUE', 'MF_VALUE', 'MTS_VALUE']
            },

            yAxis: {
                title: {
                    text: 'Среднее значение'
                }
            },

            series: [{
                name: 'Среднее',
                data: [
                	<?php echo $BEELINE_VALUE_AVERAGE[0]; ?>,
                	<?php echo $MF_VALUE_AVERAGE[0]; ?>, 
                	<?php echo $MTS_VALUE_AVERAGE[0]; ?>]
            	}]

        });
    });
</script>