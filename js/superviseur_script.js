// Graphique circulaire - Répartition des Tâches
const ctx1 = document.getElementById('taskChart').getContext('2d');
const taskChart = new Chart(ctx1, {
    type: 'doughnut',
    data: {
        labels: ['En Attente', 'Annulées', 'Effectuées'],
        datasets: [{
            label: 'Tâches',
            data: [30, 10, 60],
            backgroundColor: ['#fabd02', '#f34e4e', '#1d2b57'],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: true,
                position: 'bottom'
            }
        }
    }
});

// Graphique en barres - Durée Moyenne des Tâches
const ctx2 = document.getElementById('durationChart').getContext('2d');
const durationChart = new Chart(ctx2, {
    type: 'bar',
    data: {
        labels: ['Tâche 1', 'Tâche 2', 'Tâche 3'],
        datasets: [{
            label: 'Durée en Heures',
            data: [2, 3.5, 1.5],
            backgroundColor: '#1d2b57',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Graphique à barres - Présences & Absences
const ctx3 = document.getElementById('attendanceChart').getContext('2d');
const attendanceChart = new Chart(ctx3, {
    type: 'bar',
    data: {
        labels: ['Présents', 'Retards', 'Absents'],
        datasets: [{
            label: 'Nombre',
            data: [20, 5, 3],
            backgroundColor: ['#1d2b57', '#fabd02', '#f34e4e'],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: false
            }
        }
    }
});