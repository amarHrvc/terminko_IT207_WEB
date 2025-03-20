/**
 * Main - Front Pages
 */

window.addService = function(){
  document.querySelector('#addService').classList.remove('d-none');
};

window.closeService = function(){
  document.querySelector('#addService').classList.add('d-none');
};

window.getTimeSlots = function(value){
  console.log("🚀 ~ value:", value)
  console.log("🚀 ~ window.getTimeSlots ~ window.getTimeSlots: called");
  // document.querySelector('#addService').classList.add('d-none');
};


//timeslots
document.addEventListener('DOMContentLoaded', () => {
  console.log("🚀 ~ document.addEventListener ~ DOMContentLoaded: fired");

  const timeSlotsContainer = document.getElementById('timeSlots');
  const confirmButton = document.getElementById('confirmBooking');
  let selectedSlot = null;

  // Fetch time slots from REST endpoint (fake it with JSON data for now)
  async function fetchTimeSlots() {
    try {
      // Uncomment for rest endpoint
      // const response = await fetch('/api/timeslots'); // endpoint here
      // if (!response.ok) {
      //   throw new Error('Failed to fetch time slots');
      // }
      // const data = await response.json();
      // return data;

      // Fake data for now
      const fakeData = [
        { time: '09:00 AM - 09:30 AM', isAvailable: true },
        { time: '09:30 AM - 10:00 AM', isAvailable: true },
        { time: '10:00 AM - 10:30 AM', isAvailable: false },
        { time: '10:30 AM - 11:00 AM', isAvailable: true },
        { time: '11:00 AM - 11:30 AM', isAvailable: true },
        { time: '11:30 AM - 12:00 PM', isAvailable: true },
        { time: '12:00 PM - 12:30 PM', isAvailable: true },
        { time: '12:30 PM - 01:00 PM', isAvailable: false },
        { time: '01:00 PM - 01:30 PM', isAvailable: false },
        { time: '01:30 PM - 02:00 PM', isAvailable: true },
        { time: '02:00 PM - 02:30 PM', isAvailable: true },
        { time: '02:30 PM - 03:00 PM', isAvailable: true },
      ];
      return fakeData;
    } catch (error) {
      console.error("🚀 ~ fetchTimeSlots ~ error:", error);
      return [];
    }
  }

  // Render time slots
  function renderTimeSlots(slots) {
    console.log("🚀 ~ renderTimeSlots ~ slots:", slots);
    timeSlotsContainer.innerHTML = ''; // Clear previous slots
    slots.forEach((slot, index) => {
      const slotElement = document.createElement('div');
      slotElement.className = "col-sm-12 col-md-3 col-6";
      slotElement.innerHTML = `
        <button class="btn ${slot.isAvailable ? 'btn-outline-primary' : 'btn-secondary'} w-100" 
                data-slot="${index}" 
                ${!slot.isAvailable ? 'disabled' : ''}>
          ${slot.time}
        </button>
      `;
      timeSlotsContainer.appendChild(slotElement);
    });
  }

  // Handle slot selection
  timeSlotsContainer.addEventListener('click', (e) => {
    if (e.target.tagName === 'BUTTON' && !e.target.disabled) {
      // Deselect previously selected slot
      if (selectedSlot) {
        selectedSlot.classList.remove('btn-primary');
        selectedSlot.classList.add('btn-outline-primary');
      }

      // Select new slot
      selectedSlot = e.target;
      selectedSlot.classList.remove('btn-outline-primary');
      selectedSlot.classList.add('btn-primary');

      // Enable confirm button
      confirmButton.disabled = false;
    }
  });

  // Confirm booking
  confirmButton.addEventListener('click', () => {
    if (selectedSlot) {
      alert(`You have booked the slot: ${selectedSlot.textContent}`);
      selectedSlot.disabled = true;
      selectedSlot.classList.remove('btn-primary');
      selectedSlot.classList.add('btn-secondary');
      confirmButton.disabled = true;
      selectedSlot = null;
    }
  });

  // Initialize
  async function initialize() {
    const slots = await fetchTimeSlots();
    renderTimeSlots(slots);
  }

  initialize();
});