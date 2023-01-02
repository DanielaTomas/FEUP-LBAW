current = localStorage.getItem("o-currtab")
if (!current) localStorage.setItem("o-currtab", "tab-details") // tab-users --> Users; tab-reports --> Reports; tab-requests --> Organizer Requests
if (window.location.pathname.includes('/dashboard'))
    setEventDashboardAtiveTab(localStorage.getItem("o-currtab"))

function setEventDashboardAtiveTab(tabID){
  element = document.querySelector('#o-' + tabID)
  ulElement = document.getElementById('tabs-ul')
  aElements = ulElement.querySelectorAll("li > a")
  tabContents = document.getElementById("tabs-id").querySelectorAll(".tab-content > div")
  for (let i = 0 ; i < aElements.length; i++) {
    aElements[i].classList.remove("text-white")
    aElements[i].classList.remove("bg-gray-900")
    aElements[i].classList.add("text-gray-900")
    aElements[i].classList.add("bg-white")
    tabContents[i].classList.add("hidden")
    tabContents[i].classList.remove("block")
  }

  element.classList.remove("text-gray-900")
  element.classList.remove("bg-white")
  element.classList.add("text-white")
  element.classList.add("bg-gray-900")
  document.getElementById(tabID).classList.remove("hidden")
  document.getElementById(tabID).classList.add("block")
  localStorage.setItem("o-currtab", tabID)
}
