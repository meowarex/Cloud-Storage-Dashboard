

document.querySelectorAll('.sidebar-submenu').forEach(e => {
    e.querySelector('.sidebar-menu-dropdown').onclick = (event) => {
        event.preventDefault()
        e.querySelector('.sidebar-menu-dropdown .dropdown-icon').classList.toggle('active')

        let dropdown_content = e.querySelector('.sidebar-menu-dropdown-content')
        let dropdown_content_lis = dropdown_content.querySelectorAll('li')

        let active_height = dropdown_content_lis[0].clientHeight * dropdown_content_lis.length

        dropdown_content.classList.toggle('active')

        dropdown_content.style.height = dropdown_content.classList.contains('active') ? active_height + 'px' : '0'
    }
})





let request_options = {
    series: [{
        name: "Requests",
        data: [20, 30, 34, 20, 16, 22, 31, 51, 37, 20, 30, 34, 27]
    }],
    colors: ['#6ab04c'],

    chart: {
        height: 350,
        type: 'area',
    },
    dataLabels: {
        enabled: false
    },
    stroke: {
        curve: 'smooth',

    },

    xaxis: {
        type: 'day',
        categories: ['12:00|AM', '1:00|AM', '2:00|AM', '3:00|AM', '4:00|AM', '5:00|AM', '6:00|AM', '7:00|AM', '8:00|AM', '9:00|AM', '10:00|AM', '11:00|AM', '12:00|PM'],
    },

    legend: {

        position: 'top',
    }
}

let request_chart = new ApexCharts(document.querySelector("#request-chart"), request_options)
request_chart.render()

setDarkChart = (dark) => {
    let theme = {
        theme: {
            mode: dark ? 'dark' : 'light'
        }
    }

    request_chart.updateOptions(theme)
    extension_chart.updateOptions(theme)
}

// DARK MODE TOGGLE
let darkmode_toggle = document.querySelector('#darkmode-toggle')

darkmode_toggle.onclick = (e) => {
    e.preventDefault()
    document.querySelector('body').classList.toggle('dark')
    darkmode_toggle.querySelector('.darkmode-switch').classList.toggle('active')
    setDarkChart(document.querySelector('body').classList.contains('dark'))
}

let overlay = document.querySelector('.overlay')
let sidebar = document.querySelector('.sidebar')

document.querySelector('#mobile-toggle').onclick = () => {
    sidebar.classList.toggle('active')
    overlay.classList.toggle('active')
}

document.querySelector('#sidebar-close').onclick = () => {
    sidebar.classList.toggle('active')
    overlay.classList.toggle('active')
}