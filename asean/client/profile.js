document.addEventListener("DOMContentLoaded", async () => {
  const res = await fetch("fetch-partner-profile.php");
  const data = await res.json();
  if (data.success) {
    for (const field in data.profile) {
      const el = document.getElementById(field);
      if (el) el.textContent = data.profile[field];
    }
    document.getElementById("partnerName").textContent = data.profile.name;
    document.getElementById("profileImage").src = "../uploads/" + data.profile.profile_image;
    const expertise = data.profile.expertise.split(",").map(e => e.trim()).filter(Boolean);
    expertise.forEach(addActor);
  }
});

function editField(field) {
  const current = document.getElementById(field);
  const isParagraph = current.tagName === "P";
  const input = isParagraph ? document.createElement("textarea") : document.createElement("input");
  input.value = current.textContent;
  input.className = "border p-2 w-full rounded mt-1";
  input.onblur = async () => {
    const newVal = input.value.trim();
    const res = await fetch("update-partner-profile.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: `field=${field}&value=${encodeURIComponent(newVal)}`
    });
    const json = await res.json();
    if (json.success) current.textContent = newVal;
    current.replaceWith(current);
  };
  current.replaceWith(input);
  input.focus();
}

function logout() {
  window.location.href = "logout.php";
}

function addActor(name) {
  const selectedActors = document.getElementById("selected-actors");
  if ([...selectedActors.children].some(tag => tag.textContent.includes(name))) return;
  const tag = document.createElement("div");
  tag.className = "actor-tag";
  tag.innerHTML = `${name} <button onclick="removeActor(this)">×</button>`;
  selectedActors.appendChild(tag);
}

function removeActor(btn) {
  btn.parentElement.remove();
}

function handleKeyPress(event) {
  if (event.key === "Enter") {
    const input = document.getElementById("actor-input");
    const value = input.value.trim();
    if (value) {
      addActor(value);
      input.value = "";
    }
  }
}
