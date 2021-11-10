require('./bootstrap');

require('alpinejs');



// Tabs


window.initAutocomplete = function() {
    let tabsContainer = document.querySelector("#tabs");
  
    let tabTogglers = tabsContainer.querySelectorAll("a");
    console.log(tabTogglers);
  
    tabTogglers.forEach(function(toggler) {
      toggler.addEventListener("click", function(e) {
        e.preventDefault();
  
        let tabName = this.getAttribute("href");
  
        let tabContents = document.querySelector("#tab-contents");
  
        for (let i = 0; i < tabContents.children.length; i++) {
  
          tabTogglers[i].parentElement.classList.remove("border-blue-400", "border-b",  "-mb-px", "opacity-100");  tabContents.children[i].classList.remove("hidden");
          if ("#" + tabContents.children[i].id === tabName) {
            continue;
          }
          tabContents.children[i].classList.add("hidden");
  
        }
        e.target.parentElement.classList.add("border-blue-400", "border-b-4", "-mb-px", "opacity-100");
      });
    });
  
    document.getElementById("default-tab").click();
  }()
// END Tabs