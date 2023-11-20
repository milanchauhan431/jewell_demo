/**********************************/
// Stacked Column Chart           //
/**********************************/
$(window).on("load", function() {

    // Callback that creates and populates a data table, instantiates the stacked column chart, passes in the data and draws it.
    var stackedColumnChart = c3.generate({
        bindto: '#stacked-column',
        size: { height: 335 },
        color: {pattern: ['#22c6ab', '#fb8c00']},

        // Create the data table.
        data: 
		{
			columns: [
					['Credit',800000, 1200000, 1400000, 1300000,800000, 1200000, 1400000, 1300000,800000, 1200000, 1300000,800000],
					['Debit',200000, 400000, 500000, 300000, 400000, 500000, 200000, 400000, 500000, 300000, 400000, 500000]
			],
			type: 'bar',
			groups: [['Credit', 'Debit']]
        },
        grid: {y: {show: true }},
		axis: {
			x: {
			  type: 'category',
			  categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
			}
		}
    });

    // Instantiate and draw our chart, passing in some options.
    setTimeout(function() {stackedColumnChart.groups([ ['Credit', 'Debit'] ]); }, 1000);

    setTimeout(function() {stackedColumnChart.groups([ ['Credit', 'Debit'] ]); }, 2000);

    // Resize chart on sidebar width change
    $(".sidebartoggler").on('click', function() {stackedColumnChart.resize();});
});