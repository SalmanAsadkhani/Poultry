try {
	//Sales chart
	var ctx = document.getElementById("line-chart2");
	if (ctx) {
		ctx.height = 150;
		var myChart = new Chart(ctx, {
			type: 'line',
			data: {
				labels: ["2010", "2011", "2012", "2013", "2014", "2015", "2016"],
				type: 'line',
				defaultFontFamily: 'IRANSansDN',
				datasets: [{
					label: "غذا",
					data: [0, 30, 10, 120, 50, 63, 10],
					backgroundColor: 'transparent',
					borderColor: '#222222',
					borderWidth: 2,
					pointStyle: 'circle',
					pointRadius: 3,
					pointBorderColor: 'transparent',
					pointBackgroundColor: '#222222',
				}, {
					label: "الکترونیک",
					data: [0, 50, 40, 80, 40, 79, 120],
					backgroundColor: 'transparent',
					borderColor: '#f96332',
					borderWidth: 2,
					pointStyle: 'circle',
					pointRadius: 3,
					pointBorderColor: 'transparent',
					pointBackgroundColor: '#f96332',
				}]
			},
			options: {
				responsive: true,
				tooltips: {
					mode: 'index',
					titleFontSize: 12,
					titleFontColor: '#000',
					bodyFontColor: '#000',
					backgroundColor: '#fff',
					titleFontFamily: 'IRANSansDN',
					bodyFontFamily: 'IRANSansDN',
					cornerRadius: 3,
					intersect: false,
				},
				legend: {
					display: false,
					labels: {
						usePointStyle: true,
						fontFamily: 'IRANSansDN',
					},
				},
				scales: {
					xAxes: [{
						display: true,
						gridLines: {
							display: false,
							drawBorder: false
						},
						scaleLabel: {
							display: false,
							labelString: 'Month'
						},
						ticks: {
							fontFamily: "IRANSansDN"
						}
					}],
					yAxes: [{
						display: true,
						gridLines: {
							display: false,
							drawBorder: false
						},
						scaleLabel: {
							display: true,
							labelString: 'Value',
							fontFamily: "IRANSansDN"

						},
						ticks: {
							fontFamily: "IRANSansDN"
						}
					}]
				},
				title: {
					display: false,
					text: 'Normal Legend'
				}
			}
		});
	}


} catch (error) {
	console.log(error);
}

try {

	//line chart
	var ctx = document.getElementById("lineChart");
	if (ctx) {
		ctx.height = 150;
		var myChart = new Chart(ctx, {
			type: 'line',
			data: {
				labels: ["ژانویه", "فوریه", "مارس", "آوریل", "مه", "ژوئن", "ژولای"],
				defaultFontFamily: "IRANSansDN",
				datasets: [
					{
						label: "مجموعه داده اول من",
						borderColor: "rgba(0,0,0,.09)",
						borderWidth: "1",
						backgroundColor: "rgba(0,0,0,.07)",
						data: [22, 44, 67, 43, 76, 45, 12]
					},
					{
						label: "مجموعه داده دوم من",
						borderColor: "rgba(0, 123, 255, 0.9)",
						borderWidth: "1",
						backgroundColor: "rgba(0, 123, 255, 0.5)",
						pointHighlightStroke: "rgba(26,179,148,1)",
						data: [16, 32, 18, 26, 42, 33, 44]
					}
				]
			},
			options: {
				legend: {
					position: 'top',
					labels: {
						fontFamily: 'IRANSansDN'
					}

				},
				responsive: true,
				tooltips: {
					mode: 'index',
					intersect: false
				},
				hover: {
					mode: 'nearest',
					intersect: true
				},
				scales: {
					xAxes: [{
						ticks: {
							fontFamily: "IRANSansDN"

						}
					}],
					yAxes: [{
						ticks: {
							beginAtZero: true,
							fontFamily: "IRANSansDN"
						}
					}]
				}

			}
		});
	}


} catch (error) {
	console.log(error);
}
try {
	//bar chart
	var ctx = document.getElementById("bar-chart");
	if (ctx) {
		ctx.height = 200;
		var myChart = new Chart(ctx, {
			type: 'bar',
			defaultFontFamily: 'IRANSansDN',
			data: {
				labels: ["ژانویه", "فوریه", "مارس", "آوریل", "مه", "ژوئن", "ژولای"],
				datasets: [
					{
						label: "مجموعه داده اول من",
						data: [65, 59, 80, 81, 56, 55, 40],
						borderColor: "rgba(0, 123, 255, 0.9)",
						borderWidth: "0",
						backgroundColor: "rgba(0, 123, 255, 0.5)",
						fontFamily: "IRANSansDN"
					},
					{
						label: "مجموعه داده دوم من",
						data: [28, 48, 40, 19, 86, 27, 90],
						borderColor: "rgba(0,0,0,0.09)",
						borderWidth: "0",
						backgroundColor: "rgba(0,0,0,0.07)",
						fontFamily: "IRANSansDN"
					}
				]
			},
			options: {
				legend: {
					position: 'top',
					labels: {
						fontFamily: 'IRANSansDN'
					}

				},
				scales: {
					xAxes: [{
						ticks: {
							fontFamily: "IRANSansDN"

						}
					}],
					yAxes: [{
						ticks: {
							beginAtZero: true,
							fontFamily: "IRANSansDN"
						}
					}]
				}
			}
		});
	}


} catch (error) {
	console.log(error);
}

try {

	//radar chart
	var ctx = document.getElementById("radar-chart");
	if (ctx) {
		ctx.height = 200;
		var myChart = new Chart(ctx, {
			type: 'radar',
			data: {
				labels: [["خوردن", "شام"], ["نوشیدن", "آب"], "خوابیدن", ["طراحی", "گرافیک"], "کدنویسی", "دوچرخه سواري", "دویدن"],
				defaultFontFamily: 'IRANSansDN',
				datasets: [
					{
						label: "مجموعه داده اول من",
						data: [65, 59, 66, 45, 56, 55, 40],
						borderColor: "rgba(0, 123, 255, 0.6)",
						borderWidth: "1",
						backgroundColor: "rgba(0, 123, 255, 0.4)"
					},
					{
						label: "مجموعه داده دوم من",
						data: [28, 12, 40, 19, 63, 27, 87],
						borderColor: "rgba(0, 123, 255, 0.7",
						borderWidth: "1",
						backgroundColor: "rgba(0, 123, 255, 0.5)"
					}
				]
			},
			options: {
				legend: {
					position: 'top',
					labels: {
						fontFamily: 'IRANSansDN'
					}

				},
				scale: {
					ticks: {
						beginAtZero: true,
						fontFamily: "IRANSansDN"
					}
				}
			}
		});
	}

} catch (error) {
	console.log(error)
}

try {

	//doughut chart
	var ctx = document.getElementById("doughut-chart");
	if (ctx) {
		ctx.height = 150;
		var myChart = new Chart(ctx, {
			type: 'doughnut',
			data: {
				datasets: [{
					data: [45, 25, 20, 10],
					backgroundColor: [
						"rgba(0, 123, 255,0.9)",
						"rgba(0, 123, 255,0.7)",
						"rgba(0, 123, 255,0.5)",
						"rgba(0,0,0,0.07)"
					],
					hoverBackgroundColor: [
						"rgba(0, 123, 255,0.9)",
						"rgba(0, 123, 255,0.7)",
						"rgba(0, 123, 255,0.5)",
						"rgba(0,0,0,0.07)"
					]

				}],
				labels: [
					"سبز",
					"سبز",
					"سبز",
					"سبز"
				]
			},
			options: {
				legend: {
					position: 'top',
					labels: {
						fontFamily: 'IRANSansDN'
					}

				},
				responsive: true
			}
		});
	}


} catch (error) {
	console.log(error);
}

try {

	//pie chart
	var ctx = document.getElementById("pie-chart");
	if (ctx) {
		ctx.height = 200;
		var myChart = new Chart(ctx, {
			type: 'pie',
			data: {
				datasets: [{
					data: [45, 25, 20, 10],
					backgroundColor: [
						"rgba(0, 123, 255,0.9)",
						"rgba(0, 123, 255,0.7)",
						"rgba(0, 123, 255,0.5)",
						"rgba(0,0,0,0.07)"
					],
					hoverBackgroundColor: [
						"rgba(0, 123, 255,0.9)",
						"rgba(0, 123, 255,0.7)",
						"rgba(0, 123, 255,0.5)",
						"rgba(0,0,0,0.07)"
					]

				}],
				labels: [
					"سبز",
					"سبز",
					"سبز"
				]
			},
			options: {
				legend: {
					position: 'top',
					labels: {
						fontFamily: 'IRANSansDN'
					}

				},
				responsive: true
			}
		});
	}


} catch (error) {
	console.log(error);
}

try {
	// polar chart
	var ctx = document.getElementById("polar-chart");
	if (ctx) {
		ctx.height = 200;
		var myChart = new Chart(ctx, {
			type: 'polarArea',
			data: {
				datasets: [{
					data: [15, 18, 9, 6, 19],
					backgroundColor: [
						"rgba(0, 123, 255,0.9)",
						"rgba(0, 123, 255,0.8)",
						"rgba(0, 123, 255,0.7)",
						"rgba(0,0,0,0.2)",
						"rgba(0, 123, 255,0.5)"
					]

				}],
				labels: [
					"سبز",
					"سبز",
					"سبز",
					"سبز"
				]
			},
			options: {
				legend: {
					position: 'top',
					labels: {
						fontFamily: 'IRANSansDN'
					}

				},
				responsive: true
			}
		});
	}

} catch (error) {
	console.log(error);
}

try {

	// single bar chart
	var ctx = document.getElementById("singel-bar-chart");
	if (ctx) {
		ctx.height = 150;
		var myChart = new Chart(ctx, {
			type: 'bar',
			data: {
				labels: ["یک", "دو", "سه", "چهار", "پنج", "جمعه", "شنبه"],
				datasets: [
					{
						label: "مجموعه داده اول من",
						data: [40, 55, 75, 81, 56, 55, 40],
						borderColor: "rgba(0, 123, 255, 0.9)",
						borderWidth: "0",
						backgroundColor: "rgba(0, 123, 255, 0.5)"
					}
				]
			},
			options: {
				legend: {
					position: 'top',
					labels: {
						fontFamily: 'IRANSansDN'
					}

				},
				scales: {
					xAxes: [{
						ticks: {
							fontFamily: "IRANSansDN"

						}
					}],
					yAxes: [{
						ticks: {
							beginAtZero: true,
							fontFamily: "IRANSansDN"
						}
					}]
				}
			}
		});
	}

} catch (error) {
	console.log(error);
}