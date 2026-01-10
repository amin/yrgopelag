const select = document.querySelector("select");
console.log(select);
const cells = document.querySelectorAll("td[data-date]");

select.addEventListener("change", async () => {
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
});

select.dispatchEvent(new Event("change"));
