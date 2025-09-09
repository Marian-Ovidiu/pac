document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('myPieChart').getContext('2d');
    console.log(ctx);
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Cani k-9', 'Antibracconaggio', 'Sociale', 'Amministrazione'],
            datasets: [{
                data: [10, 20, 30, 40],
                backgroundColor: ['#84CE59', '#45752c', '#E8FCCF', '#6BAA75'],
            }]
        }
    });
});
