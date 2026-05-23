<?php ob_start(); ?>
<div class="container mt-4">
    <h1>Availability Calendar</h1>
    <p>Select an equipment to see its booked dates (red background).</p>

    <div class="row mb-4">
        <div class="col-md-4">
            <label for="equipmentSelect" class="form-label">Choose Equipment</label>
            <select id="equipmentSelect" class="form-select">
                <option value="">-- Select --</option>
                <?php if (isset($equipmentList)) : ?>
                  <?php foreach ($equipmentList as $eq): ?>
                      <option value="<?= $eq['id'] ?>"><?= htmlspecialchars($eq['name']) ?></option>
                  <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
    </div>

    <div id="calendar"></div>
</div>

<!-- FullCalendar CSS & JS -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>

<script>
    let calendar = null;
    const equipmentSelect = document.getElementById('equipmentSelect');

    function initCalendar(equipmentId) {
        if (calendar) {
            calendar.destroy();
        }
        const calendarEl = document.getElementById('calendar');
        calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,dayGridWeek'
            },
            events: equipmentId ? `?controller=equipment&action=getBookedDates&equipment_id=${equipmentId}` : [],
            eventDisplay: 'background',
            selectable: true,   // allow selecting dates (optional for booking)
            dateClick: function(info) {
                // Optional: pre-fill booking form with selected date range
                alert('Selected date: ' + info.dateStr + '. You can extend this to start booking flow.');
            }
        });
        calendar.render();
    }

    equipmentSelect.addEventListener('change', function() {
        const eqId = this.value;
        if (eqId) {
            initCalendar(eqId);
        } else {
            if (calendar) calendar.destroy();
            document.getElementById('calendar').innerHTML = '';
        }
    });

    // Optionally load first equipment by default
    if (equipmentSelect.options.length > 0 && equipmentSelect.options[0].value) {
        initCalendar(equipmentSelect.value);
    }
</script>
<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/layout.php';
?>