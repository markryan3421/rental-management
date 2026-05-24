<?php ob_start(); ?>

<style>
  /* ── Page-scoped custom properties ─────────────────────────────── */
  :root {
    --cal-accent:  #0b2ef5;   /* amber – energetic, rental-industry feel */
    --cal-danger:  #ef4444;   /* booked */
    --cal-available: #22c55e; /* available */
  }

  /* ── Page header ─────────────────────────────────────────────── */
  .cal-hero {
    background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
    border-bottom: 3px solid var(--cal-accent);
    padding: 2rem 0 1.75rem;
    margin-bottom: 2rem;
  }
  .cal-hero .badge-accent {
    background-color: var(--cal-accent);
    color: #1e293b;
    font-weight: 700;
    font-size: .7rem;
    letter-spacing: .08em;
    text-transform: uppercase;
    padding: .35em .75em;
    border-radius: 2rem;
  }
  .cal-hero h1 {
    font-size: 1.9rem;
    font-weight: 700;
    color: #f8fafc;
    margin: .4rem 0 .25rem;
    letter-spacing: -.02em;
  }
  .cal-hero p {
    color: #94a3b8;
    margin: 0;
    font-size: .95rem;
  }

  /* ── Selector card ───────────────────────────────────────────── */
  .selector-card {
    border: 1px solid rgba(255,255,255,.08);
    border-radius: .75rem;
    background: #1e293b;
  }
  .selector-card .card-header {
    background: transparent;
    border-bottom: 1px solid rgba(255,255,255,.08);
    padding: 1rem 1.25rem .875rem;
  }
  .selector-card .card-header .header-icon {
    width: 2rem;
    height: 2rem;
    border-radius: .5rem;
    background: rgba(245,158,11,.15);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: var(--cal-accent);
    font-size: 1rem;
    flex-shrink: 0;
  }
  .selector-card .form-select {
    background-color: #0f172a;
    border-color: rgba(255,255,255,.12);
    color: #f1f5f9;
    border-radius: .5rem;
    padding: .6rem 2.5rem .6rem .875rem;
    font-size: .9rem;
  }
  .selector-card .form-select:focus {
    border-color: var(--cal-accent);
    box-shadow: 0 0 0 3px rgba(245,158,11,.15);
  }
  .selector-card .form-select option { background: #1e293b; }
  .selector-card .form-label {
    color: #cbd5e1;
    font-size: .8rem;
    font-weight: 600;
    letter-spacing: .06em;
    text-transform: uppercase;
    margin-bottom: .5rem;
  }

  /* ── Legend pills ────────────────────────────────────────────── */
  .legend-dot {
    width: .75rem;
    height: .75rem;
    border-radius: 50%;
    display: inline-block;
    flex-shrink: 0;
  }
  .legend-dot.booked    { background: var(--cal-danger); }
  .legend-dot.available { background: var(--cal-available); }

  /* ── Calendar wrapper card ───────────────────────────────────── */
  .cal-card {
    border: 1px solid rgba(255,255,255,.08);
    border-radius: .75rem;
    overflow: hidden;
    background: #1e293b;
  }
  .cal-card .cal-card-header {
    background: transparent;
    border-bottom: 1px solid rgba(255,255,255,.08);
    padding: 1rem 1.25rem;
  }

  /* ── Empty / loading states ──────────────────────────────────── */
  #cal-empty-state {
    padding: 4rem 2rem;
    text-align: center;
    color: #475569;
  }
  #cal-empty-state .bi {
    font-size: 3.5rem;
    opacity: .35;
    display: block;
    margin-bottom: 1rem;
  }
  #cal-empty-state p {
    font-size: .95rem;
    color: #64748b;
    margin: 0;
  }

  /* ── FullCalendar overrides (dark theme) ─────────────────────── */
  #calendar {
    padding: 1.25rem;
  }
  /* Toolbar */
  .fc .fc-toolbar-title {
    font-size: 1.1rem !important;
    font-weight: 700 !important;
    color: #f1f5f9 !important;
    letter-spacing: -.01em;
  }
  .fc .fc-button {
    background: #0f172a !important;
    border-color: rgba(255,255,255,.12) !important;
    color: #cbd5e1 !important;
    border-radius: .4rem !important;
    font-size: .8rem !important;
    padding: .35rem .7rem !important;
    text-transform: capitalize !important;
    letter-spacing: 0 !important;
    box-shadow: none !important;
    transition: all .15s ease;
  }
  .fc .fc-button:hover,
  .fc .fc-button-active {
    background: var(--cal-accent) !important;
    border-color: var(--cal-accent) !important;
    color: #1e293b !important;
  }
  .fc .fc-button:focus { box-shadow: 0 0 0 3px rgba(245,158,11,.2) !important; }

  /* Grid */
  .fc-theme-standard td,
  .fc-theme-standard th,
  .fc-theme-standard .fc-scrollgrid {
    border-color: rgba(255,255,255,.07) !important;
  }
  .fc .fc-col-header-cell-cushion {
    color: #94a3b8 !important;
    font-size: .78rem !important;
    font-weight: 600 !important;
    text-transform: uppercase;
    letter-spacing: .06em;
    text-decoration: none !important;
  }
  .fc .fc-daygrid-day-number {
    color: #94a3b8 !important;
    font-size: .82rem;
    text-decoration: none !important;
  }
  .fc .fc-day-today {
    background: rgba(245,158,11,.08) !important;
  }
  .fc .fc-day-today .fc-daygrid-day-number {
    color: var(--cal-accent) !important;
    font-weight: 700 !important;
  }
  /* Booked background events */
  .fc-bg-event {
    background-color: rgba(239,68,68,.35) !important;
    border-left: 3px solid var(--cal-danger) !important;
    opacity: 1 !important;
  }

  /* ── Stats strip ─────────────────────────────────────────────── */
  .stat-strip {
    display: grid;
    grid-template-columns: repeat(4,1fr);
    gap: .75rem;
    padding: .875rem 1.25rem;
    border-bottom: 1px solid rgba(255,255,255,.08);
  }
  .stat-strip .stat {
    text-align: center;
  }
  .stat-strip .stat-value {
    font-size: 1.35rem;
    font-weight: 800;
    color: #f1f5f9;
    line-height: 1;
  }
  .stat-strip .stat-label {
    font-size: .72rem;
    color: #64748b;
    letter-spacing: .05em;
    text-transform: uppercase;
    margin-top: .2rem;
  }
  .stat-strip .stat-value.accent { color: var(--cal-accent); }
  .stat-strip .stat-value.danger { color: var(--cal-danger); }
  .stat-strip .stat-value.success{ color: var(--cal-available); }

  /* ── Tip / hint box ──────────────────────────────────────────── */
  .hint-box {
    border-radius: .5rem;
    background: rgba(245,158,11,.07);
    border: 1px solid rgba(245,158,11,.2);
    padding: .6rem .875rem;
    font-size: .8rem;
    color: #fcd34d;
  }
  .hint-box .bi { margin-right: .3rem; }

  /* ── "Book this" CTA button ──────────────────────────────────── */
  #book-cta {
    display: none;
  }
  #book-cta.visible { display: flex; }
  .btn-book {
    background: var(--cal-accent);
    border: none;
    color: #1e293b;
    font-weight: 700;
    border-radius: .5rem;
    padding: .55rem 1.25rem;
    font-size: .875rem;
    transition: opacity .15s;
  }
  .btn-book:hover { opacity: .88; color: #1e293b; }
  /* Past days styling */
  .fc-past-day {
    background-color: rgba(100, 116, 139, 0.15);
    cursor: not-allowed;
    opacity: 0.7;
  }
  .fc-past-day .fc-daygrid-day-number {
    color: #64748b !important;
  }
</style>

<!-- ── Page hero ──────────────────────────────────────────────────── -->
<div class="cal-hero">
  <div class="container">
    <span class="badge-accent">
      <i class="bi bi-calendar3 me-1"></i> Availability Calendar
    </span>
    <h1>Equipment Schedule</h1>
    <p>Select a piece of equipment below to view its booked and available dates.</p>
  </div>
</div>

<!-- ── Main layout ────────────────────────────────────────────────── -->
<div class="container pb-5">
  <div class="row g-4">

    <!-- ── Left column: controls ─────────────────────────────────── -->
    <div class="col-lg-3">

      <!-- Selector card -->
      <div class="card selector-card mb-3">
        <div class="card-header d-flex align-items-center gap-2">
          <span class="header-icon"><i class="bi bi-funnel-fill"></i></span>
          <span class="fw-semibold text-light" style="font-size:.9rem">Filter Equipment</span>
        </div>
        <div class="card-body">
          <label for="equipmentSelect" class="form-label">Equipment</label>
          <select id="equipmentSelect" class="form-select">
            <option value="">— Select —</option>
            <?php if (isset($equipmentList)) : ?>
              <?php foreach ($equipmentList as $eq): ?>
                <option value="<?= $eq['id'] ?>">
                  <?= htmlspecialchars($eq['name']) ?>
                </option>
              <?php endforeach; ?>
            <?php endif; ?>
          </select>
        </div>
      </div>

      <!-- Legend card -->
      <div class="card selector-card mb-3">
        <div class="card-header d-flex align-items-center gap-2">
          <span class="header-icon"><i class="bi bi-info-circle-fill"></i></span>
          <span class="fw-semibold text-light" style="font-size:.9rem">Legend</span>
        </div>
        <div class="card-body d-flex flex-column gap-2">
          <div class="d-flex align-items-center gap-2">
            <span class="legend-dot booked"></span>
            <span style="font-size:.85rem;color:#cbd5e1">Booked / Unavailable</span>
          </div>
          <div class="d-flex align-items-center gap-2">
            <span class="legend-dot available"></span>
            <span style="font-size:.85rem;color:#cbd5e1">Available</span>
          </div>
          <div class="d-flex align-items-center gap-2">
            <span class="legend-dot" style="background:rgba(245,158,11,.7)"></span>
            <span style="font-size:.85rem;color:#cbd5e1">Today</span>
          </div>
        </div>
      </div>

      <!-- Hint box -->
      <div class="hint-box mb-3">
        <i class="bi bi-lightbulb-fill"></i>
        Click any <strong>available date</strong> on the calendar to start a booking for that equipment.
      </div>

      <!-- Book CTA button (appears after equipment is chosen) -->
      <div id="book-cta" class="align-items-center gap-2">
        <a id="book-link" href="#" class="btn btn-book w-100">
          <i class="bi bi-bag-plus-fill me-1"></i> Book This Equipment
        </a>
      </div>

    </div><!-- /left col -->

    <!-- ── Right column: calendar ─────────────────────────────────── -->
    <div class="col-lg-9">
      <div class="card cal-card">

        <!-- Card header with equipment name + stats (hidden until chosen) -->
        <div id="cal-card-header" class="cal-card-header d-none">
          <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
              <span style="font-size:.75rem;color:#64748b;text-transform:uppercase;letter-spacing:.07em;font-weight:600">
                Viewing schedule for
              </span>
              <div id="cal-eq-name" class="fw-bold text-light" style="font-size:1.05rem;margin-top:.1rem">—</div>
            </div>
            <span id="cal-status-badge" class="badge rounded-pill" style="font-size:.75rem;padding:.4em .9em"></span>
          </div>
        </div>

        <!-- Stats strip (hidden until chosen) -->
        <div id="cal-stats" class="stat-strip d-none">
          <div class="stat">
              <div class="stat-value accent" id="stat-booked">—</div>
              <div class="stat-label">Booked Days</div>
          </div>
          <div class="stat">
              <div class="stat-value success" id="stat-available">—</div>
              <div class="stat-label">Free This Month</div>
          </div>
          <div class="stat">
              <div class="stat-value" id="stat-price">—</div>
              <div class="stat-label">Price / Day</div>
          </div>
          <div class="stat">                                             <!-- ← add this -->
              <div class="stat-value success" id="stat-units">—</div>
              <div class="stat-label">Units Available</div>
          </div>
      </div>

        <!-- Empty / prompt state -->
        <div id="cal-empty-state">
          <i class="bi bi-calendar-week"></i>
          <p>Select an equipment from the panel to view its availability.</p>
        </div>

        <!-- FullCalendar mount point -->
        <div id="calendar" style="display:none"></div>

      </div><!-- /cal-card -->
    </div><!-- /right col -->

  </div><!-- /row -->
</div><!-- /container -->

<!-- ── FullCalendar ───────────────────────────────────────────────── -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>

<script>
  let calendar = null;

  // Build a lookup map from the PHP equipment list
  const equipmentMeta = {};
  <?php if (isset($equipmentList)): ?>
    <?php foreach ($equipmentList as $eq): ?>
      equipmentMeta[<?= (int)$eq['id'] ?>] = {
        name:       <?= json_encode(htmlspecialchars($eq['name'])) ?>,
        status:     <?= json_encode($eq['status'] ?? 'available') ?>,
        pricePerDay:<?= (float)($eq['price_per_day'] ?? 0) ?>,
        quantity:   <?= (int)($eq['quantity'] ?? 0) ?>,
        bookingUrl: '?controller=booking&action=create&equipment_id=<?= (int)$eq['id'] ?>'
      };
    <?php endforeach; ?>
  <?php endif; ?>

  const select        = document.getElementById('equipmentSelect');
  const calEl         = document.getElementById('calendar');
  const emptyState    = document.getElementById('cal-empty-state');
  const calHeader     = document.getElementById('cal-card-header');
  const calStats      = document.getElementById('cal-stats');
  const calEqName     = document.getElementById('cal-eq-name');
  const calBadge      = document.getElementById('cal-status-badge');
  const bookCta       = document.getElementById('book-cta');
  const bookLink      = document.getElementById('book-link');
  const statBooked    = document.getElementById('stat-booked');
  const statAvailable = document.getElementById('stat-available');
  const statPrice     = document.getElementById('stat-price');
  const statUnits = document.getElementById('stat-units');

  function updateHeader(eqId) {
    const meta = equipmentMeta[eqId];
    if (!meta) return;

    calEqName.textContent = meta.name;

    const isAvailable = meta.status === 'available';
    calBadge.textContent = isAvailable ? 'Available' : 'Unavailable';
    calBadge.style.background    = isAvailable ? 'rgba(34,197,94,.15)' : 'rgba(239,68,68,.15)';
    calBadge.style.color         = isAvailable ? '#4ade80' : '#f87171';
    calBadge.style.border        = `1px solid ${isAvailable ? 'rgba(34,197,94,.3)' : 'rgba(239,68,68,.3)'}`;

    statPrice.textContent = '₱' + meta.pricePerDay.toFixed(2);
    statUnits.textContent = meta.quantity;

    bookLink.href = meta.bookingUrl;
    bookCta.classList.add('visible');

    calHeader.classList.remove('d-none');
    calStats.classList.remove('d-none');
  }

  function updateBookedStats(events) {
    const today     = new Date();
    const monthEnd  = new Date(today.getFullYear(), today.getMonth() + 1, 0);
    let bookedDays  = 0;
    const daysInMonth = monthEnd.getDate();

    const bookedSet = new Set();
    events.forEach(ev => {
      const start = new Date(ev.start);
      const end   = new Date(ev.end || ev.start);
      for (let d = new Date(start); d < end; d.setDate(d.getDate() + 1)) {
        if (d.getMonth() === today.getMonth() && d.getFullYear() === today.getFullYear()) {
          bookedSet.add(d.getDate());
        }
      }
    });
    bookedDays = bookedSet.size;
    statBooked.textContent    = bookedDays;
    statAvailable.textContent = Math.max(daysInMonth - bookedDays, 0);
  }

  function initCalendar(eqId) {
    if (calendar) { calendar.destroy(); calendar = null; }

    emptyState.style.display = 'none';
    calEl.style.display      = 'block';
    updateHeader(eqId);

    calendar = new FullCalendar.Calendar(calEl, {
      initialView: 'dayGridMonth',
      height: 'auto',
      headerToolbar: {
        left:   'prev,next today',
        center: 'title',
        right:  'dayGridMonth,dayGridWeek'
      },
      // Disable dates before today
      validRange: {
        start: new Date().toISOString().split('T')[0]  // today in YYYY-MM-DD
      },
      events: {
        url: `?controller=equipment&action=getBookedDates&equipment_id=${eqId}`,
        failure: () => console.warn('Could not load booked dates.')
      },
      eventDisplay:     'background',
      selectable:       true,
      unselectAuto:     true,
      dateClick: function(info) {
        // Prevent booking on past dates
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        const clickedDate = info.date;
        clickedDate.setHours(0, 0, 0, 0);
        if (clickedDate < today) {
          alert("You cannot book past dates.");
          return;
        }
        const meta = equipmentMeta[eqId];
        if (meta) {
          window.location.href = `?controller=booking&action=create&equipment_id=${eqId}&start_date=${info.dateStr}`;
        }
      },
      eventsSet: function(events) {
        // Update stats whenever events are rendered
        updateBookedStats(events.map(e => ({ start: e.startStr, end: e.endStr })));
      },
      // Custom day-cell styling
      dayCellClassNames: function(info) {
        const today = new Date();
        today.setHours(0,0,0,0);
        if (info.date < today) return ['fc-past-day'];
        return [];
      }
    });

    calendar.render();
  }

  function resetCalendar() {
    if (calendar) { calendar.destroy(); calendar = null; }
    calEl.style.display      = 'none';
    emptyState.style.display = '';
    calHeader.classList.add('d-none');
    calStats.classList.add('d-none');
    bookCta.classList.remove('visible');
    statBooked.textContent    = '—';
    statAvailable.textContent = '—';
    statPrice.textContent     = '—';
    statUnits.textContent     = '—';
  }

  select.addEventListener('change', function() {
    const eqId = parseInt(this.value);
    if (eqId) initCalendar(eqId);
    else resetCalendar();
  });

  // Auto-load first option if it has a value
  if (select.value && parseInt(select.value)) {
    initCalendar(parseInt(select.value));
  }
</script>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/layout.php';
?>