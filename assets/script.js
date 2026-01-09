// show/hide form, edit, delete, status toggle ====
let addT = document.getElementById("addT");
let showT = document.getElementById("showT");

addT.addEventListener("click", function() {
    document.getElementById("show_Task").setAttribute("hidden", "");
    document.getElementById("add_Task").removeAttribute("hidden");
});

showT.addEventListener("click", function() {
    document.getElementById("add_Task").setAttribute("hidden", "");
    document.getElementById("show_Task").removeAttribute("hidden");
});

document.querySelectorAll("td.pending, td.completed").forEach((cell) => {
    cell.addEventListener("click", function(e) {
        let currentStatus = e.target.textContent.trim();
        let newStatus = currentStatus === "Pending" ? "Completed" : "Pending";
        let taskId = e.target.parentElement.getAttribute("data-id");

        e.target.textContent = newStatus;
        e.target.className = newStatus.toLowerCase();

        fetch("update_status.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: `task_id=${taskId}&status=${newStatus}`,
        }).then(() => {
            updateTaskCounters();
        });
    });
});

document.querySelectorAll(".delete").forEach(btn => {
    btn.addEventListener("click", function(e) {
        e.preventDefault();
        e.stopPropagation();

        let row = e.target.closest("tr");
        let taskId = row.dataset.id;

        Swal.fire({
            title: "Delete this task?",
            text: "This action cannot be undone.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Delete",
            cancelButtonText: "Cancel",
            background: "#0f172a",
            color: "#e2e8f0"
        }).then((result) => {
            if (result.isConfirmed) {
                fetch("delete_task.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded"
                        },
                        body: `task_id=${taskId}`
                    })
                    .then(res => res.text())
                    .then(() => {
                        updateTaskTable();
                        updateTaskCounters();
                        Swal.fire({
                            title: "Deleted!",
                            text: "The task has been removed.",
                            icon: "success",
                            timer: 1000,
                            showConfirmButton: false,
                            background: "#0f172a",
                            color: "#e2e8f0",
                            heightAuto: false,
                            position: 'center',
                            customClass: {
                                popup: 'my-swal-centered'
                            }
                        });
                    });
            }
        });
    });
});


document.querySelectorAll(".edit").forEach(btn => {
    btn.addEventListener("click", function(e) {
        e.preventDefault();
        const row = btn.closest("tr");
        const titleCell = row.querySelector(".title");
        const descCell = row.querySelector(".description");
        const prioCell = row.querySelector(".priority")

        titleCell.innerHTML = `<input type="text" value="${titleCell.textContent}">`;
        descCell.innerHTML = `<textarea>${descCell.textContent}</textarea>`;
        prioCell.innerHTML = `                <select id="filter-priority">
            <option value="High">High</option>
            <option value="Medium">Medium</option>
            <option value="Low">Low</option>
        </select>`

        if (!row.querySelector(".save")) {
            const saveBtn = document.createElement("button");
            saveBtn.innerHTML = "<img style ='width:25px' src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAACXBIWXMAAAsTAAALEwEAmpwYAAABNklEQVR4nO2YTQ6CMBBGvxUeB9fK9fDnKLpz4wEk3kTc6BkcQ1ISbMACnQ5F5yWTQEqmfZm2IQMoijKUBMAewB0ATRQHsw4vdhMKUCNOvjKlSbTqGK8nCgGZeHLIuBYqIZICeJjnM4CFT7Kx4y6u1hYqOnIvfSsTWqTtPHTlTn0qIyXSlotaco+WiU1ktEyMIvaZOWLGIrVMNf7CzEUGzR/TrRW1SGFJXOYq8g2SFKn/xdbgJTN5b57r6/3htmOfc0UuJZIYmboyXFFVIu/xT8UmMjWkIpFBKhIZxHlrheqylOZGTCREJLosGwkRV5fFh3WjMmPX1/vD0GeIuOZXESZIRSxUhAlSEQsVYYJU5F9EyPHODXHN/7Mis91apaMBN6VI1rOJJ9KAk2jiBW3ASTbxFEXBJ2/AeC/4KqDlFwAAAABJRU5ErkJggg==' alt='save--v1'>";
            saveBtn.type = "button";
            saveBtn.classList.add("save");
            btn.after(saveBtn);

            saveBtn.addEventListener("click", () => {
                const newTitle = titleCell.querySelector("input").value;
                const newDesc = descCell.querySelector("textarea").value;
                const newPrio = prioCell.querySelector("select").value;

                fetch("update_task.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded"
                        },
                        body: `task_id=${row.dataset.id}&title=${newTitle}&description=${newDesc}&priority=${newPrio}`
                    }).then(res => res.text())
                    .then(data => {
                        titleCell.textContent = newTitle;
                        descCell.textContent = newDesc;
                        prioCell.textContent = newPrio
                        saveBtn.remove();
                        row.classList.add("animate__animated", "animate__shakeX");
                        setTimeout(() => row.classList.remove("animate__animated", "animate__shakeX"), 600);

                    });
            });
        }
    });
});
let statusFilter = document.getElementById("filter-status")
let priorityFilter = document.getElementById("filter-priority")
let searchInput = document.getElementById("task-search")

function filterAndSearch() {
    let statusValue = statusFilter.value;
    let priorityValue = priorityFilter.value;
    let searchText = searchInput.value.toLowerCase();

    document.querySelectorAll("table tr[data-id]").forEach(row => {
        let status = row.querySelector("td#status").textContent.trim();
        let priority = row.querySelector("td#priority").textContent.trim();
        let title = row.querySelector(".title").textContent.toLowerCase();
        let description = row.querySelector(".description").textContent.toLowerCase();

        let statusMatch = (statusValue === "all" || status === statusValue);
        let priorityMatch = (priorityValue === "all" || priority === priorityValue);
        let searchMatch = (title.includes(searchText) || description.includes(searchText));

        if (statusMatch && priorityMatch && searchMatch) {
            row.style.display = "";
        } else {
            row.style.display = "none";
        }
    });
}


statusFilter.addEventListener("change", filterAndSearch);
priorityFilter.addEventListener("change", filterAndSearch);
searchInput.addEventListener("input", filterAndSearch);

const completedCount = document.getElementById("completed-count");
const pendingCount = document.getElementById("pending-count");
const totalCount = document.getElementById("total-count");

updateTaskCounters();


//////////////////////////////////////////////////////
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
    animateNumber(completedEl, Number(completedEl.textContent) || 0, completed);
    animateNumber(pendingEl, Number(pendingEl.textContent) || 0, pending);
    animateNumber(totalEl, Number(totalEl.textContent) || 0, completed + pending);

    // tiny pulse animation on circles
    const compCircle = document.getElementById('completed-circle');
    const pendCircle = document.getElementById('pending-circle');
    const totalCircle = document.getElementById('total-circle');

    [compCircle, pendCircle, totalCircle].forEach(c => {
        c.style.transform = c.style.transform + ' scale(1.02)';
        setTimeout(() => {
            c.style.transform = c.style.transform.replace(' scale(1.02)', '');
        }, 220);
    });
}

/* call updateTaskCounters() after DOM loaded and after any change */
document.addEventListener('DOMContentLoaded', updateTaskCounters);
// Force SweetAlert2 to always append to body with fixed positioning
Swal.mixin({
    target: document.body,
    position: 'center',
    heightAuto: false,
    backdrop: true,
    customClass: {
        popup: 'sweet-centered'
    }
});