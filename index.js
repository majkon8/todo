"use strict";

flatpickr(".datepicker", {
  disableMobile: "true",
  minDate: new Date()
});

(function writeDate() {
  const fullDate = new Date();
  const day = fullDate.getDay();
  const date = fullDate.getDate();
  const month = fullDate.getMonth();
  const days = [
    "Monday",
    "Tuesday",
    "Wednsday",
    "Thursday",
    "Friday",
    "Saturday",
    "Sunday"
  ];
  const months = [
    "Jan.",
    "Feb.",
    "Mar.",
    "Apr.",
    "May",
    "June",
    "July",
    "Aug.",
    "Sept.",
    "Oct.",
    "Nov.",
    "Dec."
  ];
  const dateToDisplay = document.createTextNode(
    `${days[day]}, ${months[month]} ${date}`
  );
  document.getElementById("date").appendChild(dateToDisplay);
})();

// Adds click event listeners to check icon
const checkTaskListeners = (function checkTaskReturn() {
  document.querySelectorAll(".check").forEach(check => {
    check.onmouseover = checkToggle;
    check.onmouseleave = checkToggle;
    check.addEventListener("click", () => {
      checkToggle();
      checkUncheckTask(check);
    });
    function checkToggle() {
      check.classList.contains("far")
        ? (check.classList.remove("far"), check.classList.add("fas"))
        : (check.classList.remove("fas"), check.classList.add("far"));
    }
  });
  return checkTaskReturn;
})();

// Adds click event listeners to trash icon (deleting tasks)
const deleteTaskListener = (function deleteTaskReturn() {
  document.querySelectorAll(".trash").forEach(trash => {
    trash.onclick = () => {
      deleteTask(trash);
    };
  });
  return deleteTaskReturn;
})();

const calendarListener = (function calendarListenerReturn() {
  document.querySelectorAll(".calendar").forEach(calendar => {
    calendar.onclick = () => {
      calendar.parentElement.previousElementSibling.firstChild.focus();
    };
  });
  return calendarListenerReturn;
})();

const datePickerListener = (function datePickerListenerReturn() {
  document.querySelectorAll(".datepicker").forEach(datepicker => {
    datepicker.onchange = () => {
      addTaskWithDate(datepicker);
    };
  });
  return datePickerListenerReturn;
})();

function addTask(
  listId = "current",
  taskName = "",
  checkIconClass = "far",
  checkIconTitle = "Check task",
  // True if adding new from input or by date, false if checking/unchecking
  // It appends taskname to tasks object
  addingNew = true
) {
  // If no argument passed then taskName is taken from input
  if (taskName === "") {
    taskName = document.getElementById("input").value;
  }
  if (taskName.trim()) {
    if (addingNew) {
      tasks[listId].push(taskName);
    }
    document.getElementById("input").value = "";
    // If list which we want the task to append doesn't exist then create new
    if (document.getElementById(listId) === null) {
      createNewTasksContainer(listId);
    }
    document.getElementById(listId).innerHTML += `
                        <li class="list__item--container">
                            <div class="list__div list-transition">
                                <span class="item__text list-transition">${taskName}</span>
                            </div>
                            <div class="item__controls list-transition">
                                <span><input class="datepicker"/></span>
                                <span class="item__calendar">
                                    <i class="far fa-calendar-alt calendar list-transition" title="Set date"></i>
                                </span>
                                <span class="item__break"></span>
                                <span class="item__trash">
                                    <i class="far fa-trash-alt trash list-transition" title="Delete task"></i>
                                </span>
                                <span class="item__break"></span>
                                <span class="item__check color">
                                    <i class="${checkIconClass} fa-check-circle check" title="${checkIconTitle}"></i>
                                </span>
                            </div>
                            <div style="clear:both"></div>
                        </li>`;
    addTaskCallbacks();
  }
}

function addTaskCallbacks() {
  postTasks();
  displayHideNoTasksInfo();
  checkTaskListeners();
  deleteTaskListener();
  calendarListener();
  datePickerListener();
  slideToggleListListener();
  flatpickr(".datepicker", {
    disableMobile: "true",
    minDate: new Date()
  });
  if (tasks.current.length === 1) {
    makeSortableList();
  }
  if (localStorage.getItem("todoTheme") === "dark") {
    unsetListTransition();
    toggleListTheme(
      "rgba(255, 255, 255, 0.4)",
      "rgb(42, 42, 42)",
      "rgba(255, 255, 255, 0.4)",
      "rgba(255, 255, 255, 0.2)"
    );
    setTimeout(() => {
      setListTransition();
    }, 0);
  }
  if (localStorage.getItem("todoColor") !== null) {
    const color = localStorage.getItem("todoColor");
    changeColor(color);
  }
}

function createNewTasksContainer(listId) {
  const ul = document.createElement("ul");
  // listId is a date in string format YYYY-MM-DD OR "current" / "done"
  ul.id = listId;
  if (listId !== "current" && listId !== "done") {
    ul.classList.add("tasks__list", "tasks__list--date", "list-transition");
  } else {
    ul.classList.add("tasks__list", "list-transition");
  }
  if (document.getElementById(`${listId}-tasks`) === null) {
    const div = document.createElement("div");
    div.id = `${listId}-tasks`;
    div.classList.add("main__tasks");
    const breakDiv = document.createElement("div");
    breakDiv.classList.add("break");
    const divWithDate = document.createElement("div");
    divWithDate.classList.add(
      "tasks__date",
      "tasks__date--inactive-light",
      "list-transition"
    );
    divWithDate.innerText = changeDateFormat(listId);
    // Needed for creating new tasks container in the right place (before some date or after all)
    const thisTasksDate = new Date(listId);
    const tasksDivs = document.getElementById("main-div").children;
    let insertedBefore = false;
    // Iterating through all containers to see if there container to apppend before, i starts from 4 because first two are "current" and "done", += 2 because every second is a "break" div
    for (let i = 4; i < tasksDivs.length; i += 2) {
      // taskDivs[i].id is in format "YYYY-MM-DD-tasks" so substring(0, 10) is a date
      const anotherTasksDate = new Date(tasksDivs[i].id.substring(0, 10));
      if (thisTasksDate < anotherTasksDate) {
        document.getElementById("main-div").insertBefore(div, tasksDivs[i]);
        document
          .getElementById("main-div")
          .insertBefore(breakDiv, tasksDivs[i + 1]);
        insertedBefore = true;
        break;
      }
    }
    if (!insertedBefore) {
      document.getElementById("main-div").append(div);
      document.getElementById("main-div").append(breakDiv);
    }
    div.append(divWithDate);
  }
  document.getElementById(`${listId}-tasks`).appendChild(ul);
}

// Argument is called trash, but can be any element on the same DOM level as trash icon
// deleteOnly = true if it is deleting and not checking/unchecking or adding a date
function deleteTask(trash, deleteOnly = true) {
  // Contains span with task name
  const taskListDiv = trash.parentNode.parentNode.parentNode.firstElementChild;
  // Without this if statement there is a error which I am not sure why exists
  if (taskListDiv.parentNode.parentNode === null) {
    return;
  }
  const taskName = taskListDiv.firstElementChild.innerHTML;
  const taskUl = taskListDiv.parentNode.parentNode;
  const taskUlId = taskUl.id;
  const index = tasks[taskUlId].indexOf(taskName);
  tasks[taskUlId].splice(index, 1);
  if (
    tasks[taskUlId].length === 0 &&
    // When this element is div it means that we are deleting last task from specific date so we have to delete whole div
    taskUl.previousElementSibling.tagName === "DIV"
  ) {
    delete tasks[taskUlId];
    // First delete break div
    taskUl.parentElement.nextElementSibling.remove();
    // Then delete whole div
    taskUl.parentElement.remove();
  }
  taskListDiv.parentNode.remove();
  if (!taskUl.firstElementChild) {
    taskUl.remove();
  }
  if (deleteOnly) {
    postTasks();
  }
  displayHideNoTasksInfo();
  // Return taskName for other functions
  return taskName;
}

function addTaskWithDate(datepicker) {
  const date = datepicker.value;
  const dateObj = new Date(date);
  const today = new Date();
  const taskName = deleteTask(datepicker, false);
  if (dateObj - today < 0) {
    addTask("current", taskName, "far", "Check task", true);
    return;
  }
  typeof tasks[date] === "undefined" ? (tasks[date] = []) : null;
  addTask(date, taskName, "far", "Check task", true);
  slideToggleListListener();
}

function checkUncheckTask(check) {
  // deleteTask take trash icon as argument, but check icon works too because it is on the same DOM level. It deletes the task from done section and returns deleted task name
  const taskName = deleteTask(check, false);
  if (!taskName) {
    return;
  }
  let doneOrCurrent;
  let checkIconClass;
  let checkIconTitle;
  if (check.classList.contains("far")) {
    doneOrCurrent = "done";
    checkIconClass = "fas";
    checkIconTitle = "Uncheck task";
    tasks.done.push(taskName);
  } else {
    doneOrCurrent = "current";
    checkIconClass = "far";
    checkIconTitle = "Check task";
    tasks.current.push(taskName);
  }
  addTask(doneOrCurrent, taskName, checkIconClass, checkIconTitle, false);
}

function displayHideNoTasksInfo() {
  // Current tasks
  tasks.current.length === 0
    ? (document.getElementById("no-current").style.display = "block")
    : (document.getElementById("no-current").style.display = "none");
  // Done tasks
  tasks.done.length === 0
    ? (document.getElementById("no-done").style.display = "block")
    : (document.getElementById("no-done").style.display = "none");
}

function slideToggleList(date) {
  const listToCollapse = date.nextElementSibling;
  const listHeight = listToCollapse.scrollHeight;
  if (listHeight > 0) {
    // Slide up (hide list)
    const transition = listToCollapse.style.transition;
    listToCollapse.style.transition = "";
    listToCollapse.style.height = listHeight + "px";
    listToCollapse.style.transition = transition;
    setTimeout(() => {
      listToCollapse.style.height = 0;
      date.style.color = "";
      setTimeout(() => {
        listToCollapse.style.display = "none";
      }, 300);
    }, 0);
  } else {
    // Slide down (show list)
    const numberOfListItems = listToCollapse.childElementCount;
    const listHeight = numberOfListItems * 70;
    listToCollapse.style.display = "block";
    listToCollapse.style.height = 0;
    setTimeout(() => {
      listToCollapse.style.height = listHeight + "px";
      let color;
      localStorage.getItem("todoColor") !== null
        ? (color = localStorage.getItem("todoColor"))
        : (color = "#55be82");
      date.style.color = color;
      setTimeout(() => {
        listToCollapse.style.height = "auto";
      }, 300);
    }, 0);
  }
}

function changeDateFormat(date) {
  const dateObj = new Date(date);
  const today = new Date();
  if (dateObj - today > 0 && dateObj - today < 86400000) {
    return "Tomorrow";
  }
  return date
    .split("-")
    .reverse()
    .join(".");
}

function postTasks() {
  const url = "/todo/save.php";
  const formData = new FormData();
  formData.append("tasks", JSON.stringify(tasks));
  formData.append("authToken", authToken);
  fetch(url, {
    method: "POST",
    body: formData
  });
}

function slideToggleListListener() {
  document.querySelectorAll(".tasks__date").forEach(date => {
    date.onclick = () => {
      slideToggleList(date);
    };
  });
}

(function showAllTasks() {
  Object.keys(tasks).forEach(key => {
    for (let task of tasks[key]) {
      if (key !== "current" && key !== "done") {
        if (Date.parse(key) - Date.now() < 0) {
          tasks.current.push(tasks[key]);
          tasks.current.flat();
          delete tasks[key];
          key = "current";
        }
      }
      let checkIconTitle;
      let checkIconClass;
      key === "done"
        ? ((checkIconClass = "fas"), (checkIconTitle = "Check task"))
        : ((checkIconClass = "far"), (checkIconTitle = "Uncheck task"));
      addTask(key, task, checkIconClass, "Check task", false);
    }
  });
})();

function showMenu() {
  document.getElementById("menu-container").style.display = "flex";
  setTimeout(() => {
    document.getElementById("menu-container").style.backgroundColor =
      "rgba(0, 0, 0, 0.4)";
    document.getElementById("menu").style.width = "200px";
  }, 0);
}

function hideMenu() {
  document.getElementById("menu").style.width = "0";
  document.getElementById("menu-container").style.backgroundColor =
    "rgba(0, 0, 0, 0)";
  setTimeout(() => {
    document.getElementById("menu-container").style.display = "none";
  }, 300);
}

function isMenuOverflown() {
  return (
    document.getElementById("menu").scrollHeight >
    document.getElementById("menu").clientHeight
  );
}

function changeLogOutPosition() {
  if (isMenuOverflown()) {
    document.getElementById("log-out").classList.remove("menu__log-out");
    document
      .getElementById("log-out")
      .classList.add("menu__log-out--overflown");
  } else {
    document
      .getElementById("log-out")
      .classList.remove("menu__log-out--overflown");
    document.getElementById("log-out").classList.add("menu__log-out");
  }
}

function makeSortableList() {
  const sortableList = document.getElementById("current");
  if (sortableList !== null) {
    new Sortable(sortableList, {
      onEnd: () => {
        const taskListItems = document.getElementById("current").children;
        Array.from(taskListItems).forEach((task, index) => {
          const taskName = task.firstElementChild.firstElementChild.innerHTML;
          tasks.current[index] = taskName;
        });
        postTasks();
      },
      delay: 100,
      delayOnTouchOnly: true,
      animation: 150
    });
  }
}
makeSortableList();

function toggleTheme(
  chosenTheme,
  unchosenTheme,
  backgroundImage,
  fontColor,
  color,
  dateColor
) {
  document.getElementById(chosenTheme).style.color = "rgb(190, 70, 70)";
  document.getElementById(unchosenTheme).style.color = fontColor;
  document.getElementsByTagName(
    "body"
  )[0].style.background = `url(${backgroundImage})`;
  document.getElementById(
    "container"
  ).style.background = `url(${backgroundImage})`;
  document.getElementById("show-menu").style.color = color;
  document.getElementById("add-button").style.backgroundColor = color;
  document.getElementById("date").style.color = dateColor;
  document.getElementById("menu").style.backgroundColor = color;
  document.querySelectorAll(".menu__section").forEach(item => {
    item.style.color = fontColor;
  });
  document.querySelectorAll(".empty-indicator").forEach(item => {
    item.style.color = fontColor;
  });
  if (color === "rgb(42, 42, 42)") {
    const head = document.head;
    const link = document.createElement("link");
    link.id = "calendar-theme";
    link.type = "text/css";
    link.rel = "stylesheet";
    link.href = "https://npmcdn.com/flatpickr/dist/themes/dark.css";
    head.appendChild(link);
  } else {
    document.getElementById("calendar-theme").remove();
  }
}

function toggleListTheme(secondColor, color, fontColor, breakColor) {
  document.querySelectorAll(".item__text").forEach(item => {
    item.style.color = secondColor;
  });
  document.querySelectorAll(".list__div").forEach(item => {
    item.style.backgroundColor = color;
  });
  document.querySelectorAll(".item__controls").forEach(item => {
    item.style.backgroundColor = color;
  });
  document.querySelectorAll(".tasks__list").forEach(item => {
    item.style.color = fontColor;
  });
  document.querySelectorAll(".break").forEach(item => {
    item.style.backgroundColor = breakColor;
  });
  document.querySelectorAll(".tasks__date--inactive-light").forEach(item => {
    if (color === "rgb(42, 42, 42)") {
      item.classList.add("tasks__date--inactive-dark");
    } else {
      item.classList.remove("tasks__date--inactive-dark");
    }
  });
  document.querySelectorAll(".trash").forEach(item => {
    item.style.color = fontColor;
  });
  document.querySelectorAll(".calendar").forEach(item => {
    item.style.color = fontColor;
  });
  document.querySelectorAll(".item__break").forEach(item => {
    item.style.backgroundColor = breakColor;
  });
}

function unsetTransition() {
  document.querySelectorAll(".transition").forEach(item => {
    item.classList.add("notransition");
  });
}

function setTransition() {
  document.querySelectorAll(".transition").forEach(item => {
    item.classList.remove("notransition");
  });
}

function unsetListTransition() {
  document.querySelectorAll(".list-transition").forEach(item => {
    item.classList.add("notransition");
  });
}

function setListTransition() {
  document.querySelectorAll(".list-transition").forEach(item => {
    item.classList.remove("notransition");
  });
}

function changeColor(color) {
  document.querySelectorAll(".color").forEach(item => {
    item.style.color = color;
  });
  document.getElementById("top-bar").style.backgroundColor = color;
  document.querySelectorAll(".tasks__date").forEach(item => {
    const colorProperty = window
      .getComputedStyle(item)
      .getPropertyValue("color");
    if (
      colorProperty !== "rgba(0, 0, 0, 0.2)" &&
      colorProperty !== "rgba(255, 255, 255, 0.4)"
    ) {
      item.style.color = color;
    }
  });
}

document.getElementById("show-menu").onclick = () => {
  showMenu();
  changeLogOutPosition();
};
document.getElementById("hide-menu").onclick = hideMenu;
window.onresize = changeLogOutPosition;

document.getElementById("menu-container").onclick = e => {
  if (e.target.id === "menu-container") {
    hideMenu();
  }
};

document.getElementById("add-button").onclick = () => {
  addTask();
};

window.onkeypress = e => {
  if (e.keyCode === 13) {
    addTask();
  }
};

document.getElementById("light-theme").onclick = () => {
  localStorage.setItem("todoTheme", "light");
  toggleTheme(
    "light-theme",
    "dark-theme",
    /* Background pattern from Toptal Subtle Patterns */
    "brushed.png",
    "rgba(0,0,0,0.4)",
    "white",
    "white"
  );
  toggleListTheme("black", "white", "rgba(0,0,0,0.4)", "rgba(0, 0, 0, 0.1)");
};

document.getElementById("dark-theme").onclick = () => {
  localStorage.setItem("todoTheme", "dark");
  toggleTheme(
    "dark-theme",
    "light-theme",
    /* Background pattern from Toptal Subtle Patterns */
    "zwartevilt.png",
    "rgba(255, 255, 255, 0.4)",
    "rgb(42, 42, 42)",
    "rgba(0, 0, 0, 0.5)"
  );
  toggleListTheme(
    "rgba(255, 255, 255, 0.4)",
    "rgb(42, 42, 42)",
    "rgba(255, 255, 255, 0.4)",
    "rgba(255, 255, 255, 0.2)"
  );
};

document.querySelectorAll(".icons__icon").forEach(icon => {
  icon.onclick = e => {
    const color = window
      .getComputedStyle(e.target)
      .getPropertyValue("background-color");
    localStorage.setItem("todoColor", color);
    changeColor(color);
  };
});

if (localStorage.getItem("todoTheme") === "dark") {
  unsetTransition();
  unsetListTransition();
  document.getElementById("dark-theme").click();
  setTimeout(() => {
    setTransition();
    setListTransition();
  }, 0);
}

if (localStorage.getItem("todoColor") !== null) {
  const color = localStorage.getItem("todoColor");
  unsetTransition();
  changeColor(color);
  setTimeout(() => {
    setTransition();
  }, 0);
}
