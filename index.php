<?php
require_once "config/db.php";
echo "<h2>Database connected successfully ✅</h2>";
?>

<!-- ===== TASK COUNTERS (3D style) ===== -->
<style>
/* add these styles near top of your <style> area */
.counters-row {
  display:flex;
  gap:18px;
  justify-content:center;
  align-items:flex-start;
  margin-bottom:20px;
  flex-wrap:wrap;
}

/* each card wrapper to give 3D glass look */
.counter-card{
  width:180px;
  padding:14px;
  border-radius:18px;
  background:linear-gradient(180deg, rgba(255,255,255,0.03), rgba(255,255,255,0.015));
  box-shadow: 0 18px 40px rgba(2,6,23,0.55), inset 0 1px 0 rgba(255,255,255,0.02);
  border:1px solid rgba(255,255,255,0.04);
  display:flex;
  flex-direction:column;
  align-items:center;
  transform-style:preserve-3d;
  transition:transform .18s ease, box-shadow .18s ease;
}

/* small 3D tilt on hover */
.counter-card:hover{
  transform: translateY(-6px) translateZ(6px) scale(1.02);
  box-shadow: 0 28px 60px rgba(2,6,23,0.65);
}

/* title above the circle */
.counter-title{
  font-size:13px;
  font-weight:700;
  color: #cfeef0;
  margin-bottom:10px;
  text-align:center;
  letter-spacing:0.2px;
}

/* subtitle small */
.counter-sub{
  font-size:12px;
  color: rgba(230,238,248,0.6);
  margin-top:8px;
  text-align:center;
}

/* the circle itself */
.counter-circle{
  width:110px;
  height:110px;
  border-radius:50%;
  display:flex;
  align-items:center;
  justify-content:center;
  background: radial-gradient(circle at 30% 25%, rgba(255,255,255,0.06), transparent 20%),
              linear-gradient(135deg, rgba(6,182,212,0.12), rgba(59,130,246,0.14));
  box-shadow: 0 10px 30px rgba(3,10,30,0.55), inset 0 6px 18px rgba(255,255,255,0.03);
  border: 2px solid rgba(255,255,255,0.04);
  transform: translateZ(40px);
}

/* color variations */
.counter-circle.completed{
  background: radial-gradient(circle at 30% 25%, rgba(255,255,255,0.04), transparent 20%),
              linear-gradient(135deg, rgba(34,197,94,0.18), rgba(16,185,129,0.12));
  border-color: rgba(34,197,94,0.35);
}
.counter-circle.pending{
  background: radial-gradient(circle at 30% 25%, rgba(255,255,255,0.04), transparent 20%),
              linear-gradient(135deg, rgba(250,204,21,0.14), rgba(245,158,11,0.10));
  border-color: rgba(250,204,21,0.28);
}

/* number inside */
.counter-number{
  font-size:30px;
  font-weight:800;
  color:#e6eef8;
  text-shadow: 0 2px 8px rgba(0,0,0,0.6);
}

/* responsive */
@media(max-width:640px){
  .counters-row{gap:12px}
  .counter-card{width:140px;padding:12px}
  .counter-circle{width:90px;height:90px}
  .counter-number{font-size:24px}
}
</style>

<div class="counters-row" id="countersRow">
  <div class="counter-card" role="region" aria-label="Completed tasks">
    <div class="counter-title">Completed</div>
    <div class="counter-circle completed" id="completed-circle">
      <div class="counter-number" id="completed-count">0</div>
    </div>
    <div class="counter-sub">Tasks finished ✅</div>
  </div>

  <div class="counter-card" role="region" aria-label="Pending tasks">
    <div class="counter-title">Pending</div>
    <div class="counter-circle pending" id="pending-circle">
      <div class="counter-number" id="pending-count">0</div>
    </div>
    <div class="counter-sub">Waiting to be done ⏳</div>
  </div>

  <div class="counter-card" role="region" aria-label="Total tasks">
    <div class="counter-title">Total</div>
    <div class="counter-circle" id="total-circle">
      <div class="counter-number" id="total-count">0</div>
    </div>
    <div class="counter-sub">All tasks 📋</div>
  </div>
</div>

<script>
/* replace your existing updateTaskCounters() with this improved version */
function animateNumber(el, from, to, duration = 550) {
  const start = performance.now();
  const diff = to - from;
  function step(ts) {
    const t = Math.min(1, (ts - start) / duration);
    const val = Math.round(from + diff * (1 - Math.pow(1 - t, 3))); // easing
    el.textContent = val;
    if (t < 1) requestAnimationFrame(step);
  }
  requestAnimationFrame(step);
}

function updateTaskCounters() {
  const rows = document.querySelectorAll('table tr[data-id]');
  let pending = 0;
  let completed = 0;
  rows.forEach(row => {
    const statusTd = row.querySelector('td[id="status"], td#status') || row.querySelector('td[class*="pending"], td[class*="completed"]');
    let status = "";
    if (statusTd) status = statusTd.textContent.trim();
    if (status === "Completed") completed++;
    else pending++;
  });

  const completedEl = document.getElementById('completed-count');
  const pendingEl = document.getElementById('pending-count');
  const totalEl = document.getElementById('total-count');

  // animate numbers from current to new
  animateNumber(completedEl, Number(completedEl.textContent)||0, completed);
  animateNumber(pendingEl, Number(pendingEl.textContent)||0, pending);
  animateNumber(totalEl, Number(totalEl.textContent)||0, completed + pending);

  // tiny pulse animation on circles
  const compCircle = document.getElementById('completed-circle');
  const pendCircle = document.getElementById('pending-circle');
  const totalCircle = document.getElementById('total-circle');

  [compCircle, pendCircle, totalCircle].forEach(c => {
    c.style.transform = c.style.transform + ' scale(1.02)';
    setTimeout(()=>{ c.style.transform = c.style.transform.replace(' scale(1.02)',''); }, 220);
  });
}

/* call updateTaskCounters() after DOM loaded and after any change */
document.addEventListener('DOMContentLoaded', updateTaskCounters);
</script>
<!-- ===== end counters ===== -->
