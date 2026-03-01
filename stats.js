const statsBtn = document.querySelector('.nav-block.green');
const statsOverlay = document.getElementById('statsOverlay');
const closeStats = document.getElementById('closeStats');

let statsChart = null;

statsBtn.addEventListener('click', async () => {
    statsOverlay.classList.remove('hidden');

    const res = await fetch('stats.php');
    const data = await res.json();

    const ctx = document.getElementById('statsChart');

    if (statsChart) {
        statsChart.destroy();
    }

    statsChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Done', 'Give up'],
            datasets: [{
                data: [data.done, data.giveup],
                backgroundColor: ['#4ade80', '#f87171']
            }]
        },
        options: {
            plugins: {
                legend: {
                    labels: {
                        color: '#eaeaf0'
                    }
                }
            }
        }
    });
});

closeStats.addEventListener('click', () => {
    statsOverlay.classList.add('hidden');
});