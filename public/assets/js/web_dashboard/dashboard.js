function applyFilter(selected_value = null) {
    const filter_data = {
        quick_day: quick_day.val(),
        payment_method: payment_method.val(),
    };
    if (quick_day.val() === "custom_date") {
        filter_data["start_date"] = start_date.val();
        filter_data["end_date"] = end_date.val();
    }

    Livewire.dispatch("expense-filter-event", filter_data);
}
document.addEventListener("DOMContentLoaded", function () {
    var options = {
        chart: {
            type: "pie",
            height: 400,
            toolbar: {
                show: true,
            },
        },
        series: [], // Example values
        labels: [
            "Food & Groceries",
            "Transportation",
            "Utilities",
            "Healthcare",
            "Education",
            "Entertainment",
            "Shopping",
            "Others",
        ],
        colors: [
            "#ff8594", // Food & Groceries
            "#6fbb3e", // Transportation
            "#40bcdf", // Utilities
            "#ff8580", // Healthcare
            "#fbb20e", // Education
            "#405d9f", // Entertainment
            "#0399fe", // Shopping
            "#23c685", // Others
        ],
        dataLabels: {
            enabled: true,
            formatter: function (val, opts) {
                return "₹" + opts.w.config.series[opts.seriesIndex];
            },
            style: {
                fontSize: "12px",
            },
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return "₹" + val;
                },
            },
        },
        responsive: [
            {
                breakpoint: 768, // Tablet
                options: {
                    chart: {
                        height: 350,
                    },
                    dataLabels: {
                        style: {
                            fontSize: "10px",
                        },
                    },
                },
            },
            {
                breakpoint: 480, // Mobile
                options: {
                    chart: {
                        height: 300,
                    },
                    dataLabels: {
                        style: {
                            fontSize: "8px",
                        },
                    },
                },
            },
        ],
    };

    let chart = new ApexCharts(document.querySelector("#barChart"), options);
    chart.render();

    Livewire.on("load-data", (e) => {
        let numericSeries = e.series.map((val) => Number(val));
        chart.updateSeries(numericSeries);
    });
});
