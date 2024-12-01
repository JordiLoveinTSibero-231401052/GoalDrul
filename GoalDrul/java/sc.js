
let favoriteButton = document.getElementById('favorite');
let leagueButton = document.getElementById('league');
let upcomingButton = document.getElementById('upcoming');

favoriteButton.addEventListener('click', showLoginAlert);
leagueButton.addEventListener('click', showLoginAlert);
upcomingButton.addEventListener('click', showLoginAlert);

function showLoginAlert(event) {
  event.preventDefault();
  alert('Please log in first!');
}

function checkLogin(event) {
  const isLoggedIn = false;

  if (!isLoggedIn) {
      event.preventDefault();
      alert("You must log in first!");
      
  }
}
