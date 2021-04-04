const url = document.querySelector("[link_estadisticas]").value;

const ticketTypeUrl = url.replace("xxxxxxx", "");
const ticketPlansUrl = url.replace("xxxxxxx", "ticket_plans");
const contractPlansUrl = url.replace("xxxxxxx", "contract_plans");
let dontShow = [];

const requests = [
    //amounts
    {
        url: url,
        replace: "ticket_categories",
        title: "Amounts by category",
        target: "ticket_categories",
        type: "donut",
        label: "Categories",
        labelX: "",
        labelY: "",
        container: "grid"
    },
    {
        url: url,
        replace: "ticket_priority",
        title: "Amounts by priority",
        target: "ticket_priority",
        type: "pie",
        label: "Priority",
        labelX: "",
        labelY: "",
        container: "grid"
    },
    {
        url: url,
        replace: "ticket_type",
        title: "Amounts by type of coverage",
        target: "ticket_type",
        type: "bar",
        label: "Type of coverage",
        labelX: "Coverage",
        labelY: "Dollars",
        container: "grid"
    },
    {
        url: url,
        replace: "ticket_plans",
        title: "Amounts by plan",
        target: "ticket_plans",
        type: "bar",
        label: "Amount by plan",
        labelX: "Plan",
        labelY: "Dollars",
        container: "grid"
    },
    //numbers
    {
        url: url,
        replace: "contract_plans",
        title: "Number of contracts by plan",
        target: "contract_plans",
        type: "bar",
        label: "Contracts by plan",
        labelX: "",
        labelY: "",
        container: "contract_grid"
    },
    {
        url: url,
        replace: "ticket_number_plans",
        title: "Number of tickets by plan",
        target: "ticket_number_plans",
        type: "bar",
        label: "Tickets by plan",
        labelX: "",
        labelY: "",
        container: "contract_grid"
    },
]

function chartRequest(_start, _end, _contract) {
    requests.forEach(item => {
        console.log(dontShow.includes(item.replace));
        if (!dontShow.includes(item.replace)) {
            postRequest(item.url, { type: item.replace, start: _start, end: _end, contract: _contract }).done(function (result) {
                // if (result.success) {
                generateChart(item.title, item.target, item.type, result.labels, [{ label: item.label, data: result.data }], item.labelX, item.labelY, item.container)
                if (!result.success && typeof result.name != "undefined" && typeof result.message != "undefined") {
                    errorMessage(result.name, result.message)
                }
            })
        }
    });
}

function generateChart(title, target, type = "pie", labels = [],
    datasets = [{ label: "Dataset 1", data: [] }], labelX = "", labelY = "", container = "grid") {
    typeChars = ["bar", "pie", "line", "doughnut", "donut"]
    if (typeof title == "string" && typeof target == "string" && typeof type == "string"
        && Array.isArray(labels) && Array.isArray(datasets) && typeChars.includes(type)) {

        const grid = document.querySelector(`[id='${container}']`)
        if (grid) {
            const item = document.createElement("div")

            item.className = "item";
            item.innerHTML = `<canvas id="${target}" width="400px" height="400px"></canvas>`;

            grid.append(item);

            const element = document.querySelector(`[id="${target}"]`).getContext('2d');

            let maxData = 0;

            type = type == "donut" ? "doughnut" : type;

            datasets.forEach(set => {
                set.borderWidth = 1;
                set.label = set.label ? set.label : "No name";
                if (type == "line") {
                    set.fill = false;
                    set.borderWidth = 3;
                    const color = getRandomRGBAColor(1);
                    set.backgroundColor = color;
                    set.borderColor = color;
                } else {
                    set.data.forEach(data => {
                        const opacity = 0.5
                        const rgbNumbers = getRandomRGB();
                        const rgba = `rgba(${rgbNumbers},${opacity})`;
                        const rgb = `rgb(${rgbNumbers})`;

                        maxData = Math.max(data);

                        if (Array.isArray(set.backgroundColor)) {
                            set.backgroundColor.push(rgba)
                        } else {
                            set.backgroundColor = [];
                            set.backgroundColor.push(rgba)
                        }
                        if (Array.isArray(set.borderColor)) {
                            set.borderColor.push(rgb)
                        } else {
                            set.borderColor = [];
                            set.borderColor.push(rgb)
                        }
                    });
                }
            });
            let options = {
                responsive: true,
                title: {
                    display: true,
                    text: title
                },
                // tooltips: {
                //     mode: 'index',
                //     intersect: false,
                // },
                // hover: {
                //     // mode: 'nearest',
                //     // intersect: true
                // },
            }

            if (type == "line" || type == "bar") {
                options.scales = {
                    xAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: labelX
                        },

                    }],
                    yAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: labelY
                        },
                        ticks: {
                            beginAtZero: true,
                            sugestedMax: maxData + (maxData / 4)
                        }
                    }]
                }
            }
            const config = {
                type: type,
                data: {
                    labels: labels,
                    datasets: datasets,
                },
                options: options
            };
            return new Chart(element, config);
        }
    } else {
        console.log("No se pudo cargar el objeto de estadisticas")
    }
}


function getRandomHexColor() {
    var letters = '0123456789ABCDEF';
    var color = '#';
    for (var i = 0; i < 6; i++) {
        color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
}

function getRandomRGBAColor(opacity) {
    return `rgba(${getRandomRGB()},${opacity})`;
}


function getRandomRGB() {
    return `${Math.floor(Math.random() * 255)},${Math.floor(Math.random() * 255)},${Math.floor(Math.random() * 255)}`;
}

$("[search-button]").on("click", function (e) {
    e.preventDefault()
    if ($(`[name="start"]`).val() && $(`[name="end"]`).val()) {
        changeDate($(`[name="start"]`).val(), $(`[name="end"]`).val(), $(`[name="contract"]`).val())
    } else {
        errorMessage('The fields with * are required')
    }
})

function changeDate(start, end, contract) {
    const contractGrid = document.querySelector(`#contract_grid`)
    const amountGrid = document.querySelector(`#grid`)
    if (contractGrid) {
        contractGrid.innerHTML = "";
    }
    if (amountGrid) {
        amountGrid.innerHTML = "";
    }
    chartRequest(start, end, contract);
}


$(`[name="no-statistic"]`).each(function (params) {
    dontShow.push($(this).val());
})

changeDate($(`[name="start"]`).val(), $(`[name="end"]`).val(), $(`[name="contract"]`).val())
