function googlePieChart(){
    google.load("visualization", "1", {packages:["corechart"]});
    google.setOnLoadCallback(drawChart);
    function drawChart() {
        var $el = $('#piechart');
        var goalAmount = $el.data('goalamount');
        var amountRaised = $el.data('amountraised');
        var amountLeft = Math.max(0,goalAmount-amountRaised);
        log(goalAmount);
        log(amountRaised);
        log(amountLeft);
        var data = google.visualization.arrayToDataTable([
          ['Amount', 'Money'],
          ['Amount Raised',     amountRaised],
          ['Amount Left',      amountLeft],
        ]);

        
        var options = {
          title: '',
          is3D: true,
          backgroundColor: $('.report-row').css('background-color')
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));
        chart.draw(data, options);
      }
}

googlePieChart();

