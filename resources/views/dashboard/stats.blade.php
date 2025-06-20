@extends('layouts.app')

@section('title', 'Statistiques')

@section('content')
<!-- Main Content -->
<div class="library-container">
  <!-- Summary Cards -->
  <div class="library-stats-grid">
    <div class="library-stat-card">
      <div class="library-stat-icon">
        <i class="fas fa-book-open"></i>
      </div>
      <div class="library-stat-content">
        <h3>Prêts Actifs</h3>
        <div class="library-stat-value">{{ $activeprets ?? 0 }}</div>
        <div class="library-stat-change positive">
          <i class="fas fa-arrow-up"></i>
          +12% ce mois
        </div>
      </div>
    </div>

    <div class="library-stat-card">
      <div class="library-stat-icon">
        <i class="fas fa-clock"></i>
      </div>
      <div class="library-stat-content">
        <h3>Prêts Retardés</h3>
        <div class="library-stat-value">{{ $delayedprets ?? 0 }}</div>
        <div class="library-stat-change negative">
          <i class="fas fa-arrow-down"></i>
          -5% ce mois
        </div>
      </div>
    </div>

    <div class="library-stat-card">
      <div class="library-stat-icon">
        <i class="fas fa-check-circle"></i>
      </div>
      <div class="library-stat-content">
        <h3>Livres Disponibles</h3>
        <div class="library-stat-value">{{ $availableBooks ?? 0 }}</div>
        <div class="library-stat-change positive">
          <i class="fas fa-arrow-up"></i>
          +3% ce mois
        </div>
      </div>
    </div>
  </div>

  
 <div class="library-tabs">
<a href="#" class="library-tab" onclick="filterByPeriod(event, 'day')">Aujourd'hui</a>
<a href="#" class="library-tab" onclick="filterByPeriod(event, 'week')">Cette Semaine</a>
<a href="#" class="library-tab" onclick="filterByPeriod(event, 'month')">Ce Mois</a>
<a href="#" class="library-tab" onclick="filterByPeriod(event, 'year')">Cette Année</a>

  <!-- Charts Section -->
  <div>
    <!-- Arabic Statistics Table -->
    <div class="library-chart-card">
        <!-- Time Period Tabs -->
  <div class="library-tabs">
      <h2 class="mb-4 text-center" >📊 جدول إحصائيات الإعارة - {{ now()->format('F Y') }}</h2>

      <table class="table table-bordered text-center">
          <thead class="table-dark">
              <tr>
                  <th>التاريخ</th>
                  <th>عدد الطلبات الكلي</th>
                  <th>عدد الطلبات حسب الفئة<br>( قراء ) (موظف)</th>
                    <th>الطلبات المعلقة</th>
                  <th>الطلبات المستجابة حسب الفئة</th>
                  <th>الكتب المعادة <br>( قراء ) (موظف)</th>
                  <th> الكتب المعادة  عدد  الكلي</th>
              </tr>
          </thead>
          <tbody>
              @forelse($dailyStats as $stat)
              <tr>
                  <td>{{ $stat['date'] }}</td>
                  <td><span >{{ $stat['total_requests'] }}</span></td>
                  <td>
                      <span class="badge bg-info">{{ $stat['reader_requests'] }}</span> - 
                      <span class="badge bg-warning">{{ $stat['employee_requests'] }}</span>
                  </td>
                  <td>{{ $stat['pending_requests'] ?? 0 }}</td>
                  <td>
                      <span class="badge bg-success">{{ $stat['approved_readers'] }}</span> - 
                      <span class="badge bg-success">{{ $stat['approved_employees'] }}</span>
                  </td>
                  <td>
                      <span >{{ $stat['returned_readers'] }}</span> - 
                      <span >{{ $stat['returned_employees'] }}</span>
                  </td>
                  <td><span class="badge bg-dark">{{ $stat['total_returned'] }}</span></td>
              </tr>
              @empty
              <tr>
                  <td colspan="6" class="text-muted">لا توجد بيانات للعرض</td>
              </tr>
              @endforelse
          </tbody>
      </table>
    </div>

    <!-- Most Demanded Books -->
    <div class="library-chart-card">
      <h3 class="library-chart-title">
        <i class="fas fa-star"></i> Livres les Plus Demandés
      </h3>
      <div class="chart-container">
        <canvas id="popularBooksChart"></canvas>
      </div>
      <div class="library-book-list">
        @foreach($popularBooks as $book)
        <div class="library-book-item">
          <span class="library-book-rank">{{ $loop->iteration }}</span>
          <span class="library-book-title">{{ $book->doc_titre }}</span>
          <span class="library-book-count">{{ $book->pret_count }} prêts</span>
        </div>
        @endforeach
      </div>
    </div>
  </div>

  <!-- Search Statistics -->
  <div class="library-stats-card">
    <h3 class="library-stats-title">
      <i class="fas fa-search"></i> Statistiques de Recherche
    </h3>
    <div class="library-search-stats">
      <div class="library-search-chart">
        <canvas id="searchStatsChart"></canvas>
      </div>
      <div class="library-search-terms">
        <h4>Termes les Plus Recherchés</h4>
        <ul>
          @foreach($popularSearches as $search)
          <li class="search-term-item">
            <span class="library-search-term">{{ $search->query }}</span>
            <span class="library-search-count">{{ $search->count }} fois</span>
          </li>
          @endforeach
        </ul>
      </div>
    </div>
  </div>

  <!-- Categories Statistics -->
  <div class="library-stats-card">
    <h3 class="library-stats-title">
      <i class="fas fa-tags"></i> Statistiques par Catégorie
    </h3>
    <div class="library-categories-grid">
      <div class="library-categories-chart">
        <canvas id="categoriesChart"></canvas>
      </div>
      <div class="library-categories-list">
        @foreach($categories as $category)
        <div class="library-categories-item">
          <div class="category-header">
            <span class="library-categories-name">{{ $category->nom ?? 'Sans catégorie' }}</span>
            <span class="library-categories-count">{{ $category->pret_count }} prêts</span>
          </div>
          <div class="library-categories-bar">
            <div class="library-categories-progress" style="width: {{ $category->pret_count > 0 ? min(($category->pret_count / ($categories->max('pret_count') ?: 1)) * 100, 100) : 0 }}%"></div>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </div>

  <!-- Detailed pret History -->
  <div class="library-stats-card">
    <h3 class="library-stats-title">
      <i class="fas fa-list"></i> Détail des Prêts
    </h3>
    <div class="library-table-container">
      <table class="library-data-table">
        <thead>
          <tr>
            <th>Date</th>
            <th>Lecteur</th>
            <th>Titre</th>
            <th>Catégorie</th>
            <th>Statut</th>
          </tr>
        </thead>
        <tbody>
          @foreach($pretHistory as $pret)
          <tr>
            <td>{{ \Carbon\Carbon::parse($pret->date_pret)->format('d/m/Y') }}</td>
            <td>{{ $pret->lecteur->lec_nom ?? 'Inconnu' }}</td>
            <td>{{ $pret->exemplaire->notice->doc_titre ?? 'Titre inconnu' }}</td>
            <td>{{ $pret->exemplaire->notice->categorie->nom ?? '—' }}</td>
            <td>
              <span class="library-status-badge {{ $pret->statut == 'retourne' ? 'returned' : ($pret->statut == 'en_retard' ? 'delayed' : 'active') }}">
                {{ ucfirst(str_replace('_', ' ', $pret->statut)) }}
              </span>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
 

// Fixed JavaScript for the stats page
document.addEventListener('DOMContentLoaded', function() {
    // Initialize charts after page load
    initializeCharts();
    
    // Set default active tab
    const monthTab = document.querySelector('.library-tab[onclick*="month"]');
    if (monthTab) {
        monthTab.classList.add('active');
    }
});

function initializeCharts() {
    // Popular Books Chart
    const bookCtx = document.getElementById('popularBooksChart');
    if (bookCtx) {
        const popularBooksChart = new Chart(bookCtx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: @json($popularBooks->pluck('doc_titre')->take(5)),
                datasets: [{
                    label: 'Prêts',
                    data: @json($popularBooks->pluck('pret_count')->take(5)),
                    backgroundColor: ['#667eea', '#f6ad55', '#38b2ac', '#9f7aea', '#48bb78'],
                    borderWidth: 0,
                    hoverOffset: 30
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '60%',
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    }

    // Search Statistics Chart
    const searchCtx = document.getElementById('searchStatsChart');
    if (searchCtx) {
        const searchStatsChart = new Chart(searchCtx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: @json($popularSearches->pluck('query')->take(7)),
                datasets: [{
                    label: 'Recherches',
                    data: @json($popularSearches->pluck('count')->take(7)),
                    backgroundColor: '#f6ad55'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });
    }

    // Categories Chart
    const categoriesCtx = document.getElementById('categoriesChart');
    if (categoriesCtx) {
        const categoriesChart = new Chart(categoriesCtx.getContext('2d'), {
            type: 'pie',
            data: {
                labels: @json($categories->pluck('nom')),
                datasets: [{
                    label: 'Prêts par catégorie',
                    data: @json($categories->pluck('pret_count')),
                    backgroundColor: [
                        '#667eea', '#f6ad55', '#38b2ac', '#9f7aea', '#48bb78',
                        '#e53e3e', '#dd6b20', '#3182ce', '#2f855a', '#d53f8c'
                    ],
                    borderWidth: 0,
                    hoverOffset: 40
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'right' }
                }
            }
        });
    }
}

// Function to filter data by period
function filterByPeriod(event, period) {
    if (event) {
        event.preventDefault();
    }

    console.log('Filter called with period:', period);

    // Update active tab
    document.querySelectorAll('.library-tab').forEach(tab => tab.classList.remove('active'));
    if (event && event.target) {
        event.target.classList.add('active');
    }

    // Show loading state
    const tableContainer = document.querySelector('.library-chart-card');
    if (tableContainer) {
        tableContainer.style.opacity = '0.6';
    }

    // Make AJAX request
    fetch(`/stats/filter/${period}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);
        
        if (!response.ok) {
            return response.text().then(text => {
                console.error('Response text:', text);
                throw new Error(`HTTP ${response.status}: ${response.statusText} - ${text}`);
            });
        }
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        
        if (data.success && data.table_html) {
            // Update table content
            const tableCard = document.querySelector('.library-chart-card');
            if (tableCard) {
                tableCard.innerHTML = data.table_html;
            }
            console.log('Table updated successfully');
        } else {
            console.error('Data error:', data);
            throw new Error(data.message || data.error || 'Unknown error occurred');
        }
    })
    .catch(error => {
        console.error('Filter error:', error);
        
        // Show user-friendly error message
        const tableCard = document.querySelector('.library-chart-card');
        if (tableCard) {
            tableCard.innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    Erreur lors du chargement des données: ${error.message}
                    <br><small>Vérifiez la console pour plus de détails</small>
                </div>
            `;
        }
    })
    .finally(() => {
        // Remove loading state
        const tableContainer = document.querySelector('.library-chart-card');
        if (tableContainer) {
            tableContainer.style.opacity = '1';
        }
    });
}
</script>

<style>
/* Additional CSS for the Arabic table */
.table th, .table td {
    vertical-align: middle;
    padding: 12px;
}

.badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
}

.library-chart-card {
    background: white;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 4px rgba(172, 123, 123, 0.1);
}

.chart-container {
    height: 300px;
    margin: 20px 0;
}
  .library-container {
    max-width: 1200px;
    margin: 1.5rem auto;
    padding: 1.5rem;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    color: #222;
    background-color: #fafafa;
  }

  .dashboard-header {
    text-align: center;
    margin-bottom: 2rem;
  }
  .dashboard-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #667eea;
  }
  .dashboard-title i {
    margin-right: 0.5rem;
  }
  .dashboard-subtitle {
    font-size: 1.1rem;
    color: #666;
  }

  /* Summary Cards Grid */
  .library-stats-grid {
    display: flex;
    justify-content: space-between;
    gap: 1rem;
    margin-bottom: 2rem;
    flex-wrap: wrap;
  }

  .library-stat-card {
    flex: 1 1 calc(33.333% - 1rem);
    background-color: #fff;
    border-radius: 12px;
    box-shadow: 0 6px 12px rgba(136, 147, 194, 0.15);
    padding: 1rem 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: background-color 0.3s ease;
  }
  .library-stat-card:hover {
    background-color: #f0f3ff;
  }
  .library-stat-icon {
    font-size: 2.8rem;
    color: #667eea;
    flex-shrink: 0;
  }
  .library-stat-content h3 {
    font-weight: 600;
    font-size: 1.1rem;
    margin-bottom: 0.2rem;
  }
  .library-stat-value {
    font-size: 2rem;
    font-weight: 700;
    color: #222;
  }
  .library-stat-change {
    font-size: 0.85rem;
    display: flex;
    align-items: center;
    gap: 0.2rem;
    font-weight: 600;
  }
  .library-stat-change.positive {
    color: #48bb78;
  }
  .library-stat-change.negative {
    color: #e53e3e;
  }

  /* Tabs */
  .library-tabs {
    display: flex;
    justify-content: center;
    gap: 1rem;
    margin-bottom: 2rem;
    flex-wrap: wrap;
  }
  .library-tab {
    background-color: #f0f3ff;
    border: none;
    border-radius: 30px;
    padding: 0.5rem 1.5rem;
    font-weight: 600;
    color: #667eea;
    cursor: pointer;
    box-shadow: 0 4px 10px rgba(136, 153, 227, 0.2);
    transition: background-color 0.3s ease, color 0.3s ease;
  }
  .library-tab:hover {
    background-color: #667eea;
    color: white;
  }
  .library-tab.active {
    background-color: #667eea;
    color: white;
  }

  /* Charts Section */
  .library-chart-card {
    background-color: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 14px rgba(102, 126, 234, 0.1);
    padding: 1.5rem;
    margin-bottom: 2rem;
  }
  .library-chart-title {
    font-weight: 700;
    font-size: 1.3rem;
    color: #667eea;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }
  .library-chart-title i {
    font-size: 1.4rem;
  }
  .chart-container {
    height: 320px;
    position: relative;
  }

  /* Popular Books List */
  .library-book-list {
    margin-top: 1rem;
    border-top: 1px solid #eee;
    padding-top: 1rem;
  }
  .library-book-item {
    display: flex;
    justify-content: space-between;
    padding: 0.25rem 0;
    font-weight: 600;
    color: #444;
  }
  .library-book-rank {
    color: #667eea;
    font-weight: 700;
    margin-right: 0.5rem;
  }

  /* Search Stats */
  .library-stats-card {
    background-color: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 14px rgba(102, 126, 234, 0.1);
    padding: 1.5rem;
    margin-bottom: 2rem;
  }
  .library-stats-title {
    font-weight: 700;
    font-size: 1.3rem;
    color: #667eea;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }
  .library-stats-title i {
    font-size: 1.4rem;
  }
  .library-search-stats {
    display: flex;
    gap: 2rem;
    flex-wrap: wrap;
  }
  .library-search-chart {
        display: block;
    flex: 1 1 350px;
    width: 100%;
    height: 280px;
    position: relative;
  }
  .library-search-terms {
    flex: 1 1 250px;
  }
  .library-search-terms h4 {
    font-weight: 600;
    color: #667eea;
    margin-bottom: 0.5rem;
  }
  .search-term-item {
    display: flex;
    justify-content: space-between;
    padding: 0.25rem 0;
    font-weight: 600;
    color: #444;
  }
  .library-search-term {
    font-style: italic;
  }
  .library-search-count {
    color: #f6ad55;
  }

  /* Categories Grid */
  .library-categories-grid {
    display: flex;
    gap: 2rem;
    flex-wrap: wrap;
  }
  .library-categories-chart {
    DISPLAY: BLOCK;
    width: 100%;
    flex: 1 1 350px;
    height: 280px;
    position: relative;
  }
  .library-categories-list {
    flex: 1 1 400px;
    max-height: 280px;
    overflow-y: auto;
  }
  .library-categories-item {
    margin-bottom: 1rem;
  }
  .category-header {
    display: flex;
    justify-content: space-between;
    font-weight: 600;
    color: #444;
  }
  .library-categories-bar {
    background-color: #f0f3ff;
    border-radius: 8px;
    height: 12px;
    margin-top: 0.3rem;
    overflow: hidden;
  }
  .library-categories-progress {
    background-color: #667eea;
    height: 12px;
    border-radius: 8px;
  }

  /* Table */
  .library-table-container {
    overflow-x: auto;
  }
  .library-data-table {
    width: 100%;
    border-collapse: collapse;
  }
  .library-data-table thead {
    background-color: #667eea;
    color: white;
  }
  .library-data-table th,
  .library-data-table td {
    text-align: left;
    padding: 0.6rem 1rem;
    border-bottom: 1px solid #eee;
  }
  .library-status-badge {
    padding: 0.3rem 0.6rem;
    border-radius: 12px;
    font-weight: 700;
    font-size: 0.8rem;
    text-transform: capitalize;
  }
  .library-status-badge.returned {
    background-color: #48bb78;
    color: white;
  }
  .library-status-badge.delayed {
    background-color: #e53e3e;
    color: white;
  }
  .library-status-badge.active {
    background-color: #f6ad55;
    color: white;
  }

  /* Responsive */
  @media (max-width: 900px) {
    .library-stats-grid {
      flex-direction: column;
    }
    .library-stat-card {
      flex: 1 1 100%;
    }
    .library-search-stats {
            display: BLOCK;
      flex-direction: column;
    }
    .library-categories-grid {
      flex-direction: column;
    }
  }

</style>
@endsection