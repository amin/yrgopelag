document.addEventListener("DOMContentLoaded", () => {
  const select = document.querySelector("select");
  const cells = document.querySelectorAll("td[data-date]");
  const arrivalDate = document.getElementById("arrival_date");
  const departureDate = document.getElementById("departure_date");
  const featureCheckboxes = document.querySelectorAll(
    'input[name="features[]"]'
  );
  const totalDisplay = document.getElementById("total_price");

  function calculateTotal() {
    let total = 0;

    const selectedOption = select.options[select.selectedIndex];
    const roomPrice = parseInt(selectedOption.dataset.price) || 0;

    const arrival = new Date(arrivalDate.value);
    const departure = new Date(departureDate.value);
    const nights = (departure - arrival) / (1000 * 60 * 60 * 24);

    if (nights > 0) {
      total += roomPrice * nights;
    }

    featureCheckboxes.forEach((cb) => {
      if (cb.checked) {
        total += (parseInt(cb.dataset.price) || 0) * nights;
      }
    });

    totalDisplay.textContent = `${total} credits`;
  }

  async function updateCalendar() {
    const res = await fetch("/api/getBookings.php");
    const data = await res.json();
    const bookings = data[select.value] || [];

    cells.forEach((cell) => {
      const day = parseInt(cell.dataset.date);
      const isBooked = bookings.some((b) => {
        const start = new Date(b.arrival_date).getDate();
        const end = new Date(b.departure_date).getDate();
        return day >= start && day < end;
      });
      cell.classList.toggle("booked", isBooked);
    });
  }

  select.addEventListener("change", () => {
    calculateTotal();
    updateCalendar();
  });

  arrivalDate.addEventListener("change", calculateTotal);
  departureDate.addEventListener("change", calculateTotal);
  featureCheckboxes.forEach((cb) =>
    cb.addEventListener("change", calculateTotal)
  );

  calculateTotal();
  updateCalendar();
});
