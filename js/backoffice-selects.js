// Styled, keyboard-accessible select menus. Native controls remain the form's source of truth.
(() => {
  let opened = null;
  for (const select of document.querySelectorAll("select")) {
    if (!select.id) continue;
    const label = select.labels[0];
    if (!label) continue;
    const wrapper = document.createElement("div");
    wrapper.className = "select-control";
    select.before(wrapper);
    wrapper.append(select);
    select.classList.add("native-select");
    select.tabIndex = -1;
    select.setAttribute("aria-hidden", "true");

    const button = document.createElement("button");
    button.type = "button";
    button.className = "select-trigger";
    button.id = `${select.id}-control`;
    button.setAttribute("role", "combobox");
    button.setAttribute("aria-haspopup", "listbox");
    button.setAttribute("aria-expanded", "false");
    button.setAttribute("aria-controls", `${select.id}-options`);
    if (select.required) button.setAttribute("aria-required", "true");
    button.disabled = select.disabled;
    label.id ||= `${select.id}-label`;
    label.htmlFor = button.id;
    const value = document.createElement("span");
    value.className = "select-value";
    value.id = `${select.id}-value`;
    button.setAttribute("aria-labelledby", `${label.id} ${value.id}`);
    const arrow = document.createElement("span");
    arrow.className = "select-chevron";
    arrow.setAttribute("aria-hidden", "true");
    button.append(value, arrow);

    const list = document.createElement("div");
    list.id = `${select.id}-options`;
    list.className = "select-options";
    list.setAttribute("role", "listbox");
    list.setAttribute("aria-labelledby", label.id);
    list.hidden = true;
    const error = document.createElement("p");
    error.id = `${select.id}-error`;
    error.className = "select-error";
    error.setAttribute("role", "alert");
    error.hidden = true;
    wrapper.append(button, list, error);

    let active = -1,
      typed = "",
      lastTyped = 0;
    const options = [...select.options].map((option, index) => {
      const item = document.createElement("div");
      item.id = `${select.id}-option-${index}`;
      item.className = "select-option";
      item.setAttribute("role", "option");
      item.tabIndex = -1;
      item.textContent = option.textContent;
      item.hidden = option.hidden;
      const disabled =
        option.disabled || (select.required && option.value === "");
      item.setAttribute("aria-disabled", String(disabled));
      item.addEventListener("click", () => {
        if (!disabled) choose(index);
      });
      item.addEventListener("pointermove", () => {
        if (!disabled) highlight(index, false);
      });
      list.append(item);
      return { option, item, disabled };
    });
    const enabled = () =>
      options
        .map((_, index) => index)
        .filter(
          (index) => !options[index].disabled && !options[index].option.hidden,
        );
    function highlight(index, scroll = true) {
      active = index;
      options.forEach(({ item }, i) =>
        item.classList.toggle("is-highlighted", i === active),
      );
      if (active >= 0) {
        button.setAttribute("aria-activedescendant", options[active].item.id);
        if (scroll) options[active].item.scrollIntoView({ block: "nearest" });
      }
    }
    function render() {
      value.textContent = select.selectedOptions[0]?.textContent || "";
      options.forEach(({ item }, index) =>
        item.setAttribute(
          "aria-selected",
          String(index === select.selectedIndex),
        ),
      );
      if (select.validity.valid) {
        button.removeAttribute("aria-invalid");
        button.removeAttribute("aria-describedby");
        wrapper.classList.remove("has-error");
        error.hidden = true;
      }
    }
    function close(focus = false) {
      list.hidden = true;
      wrapper.classList.remove("is-open", "opens-up");
      button.setAttribute("aria-expanded", "false");
      button.removeAttribute("aria-activedescendant");
      if (opened?.button === button) opened = null;
      if (focus) button.focus();
    }
    function open() {
      if (button.disabled) return;
      if (opened?.button !== button) opened?.close();
      list.hidden = false;
      wrapper.classList.add("is-open");
      wrapper.classList.remove("opens-up");
      button.setAttribute("aria-expanded", "true");
      opened = { button, close };
      const position = button.getBoundingClientRect();
      const footer =
        document.querySelector(".save-bar")?.getBoundingClientRect().height ||
        0;
      if (
        innerHeight - footer - position.bottom < list.offsetHeight &&
        position.top > list.offsetHeight
      )
        wrapper.classList.add("opens-up");
      const selected = select.selectedIndex;
      highlight(
        options[selected] && !options[selected].disabled
          ? selected
          : enabled()[0],
      );
    }
    function choose(index) {
      const changed = select.selectedIndex !== index;
      select.selectedIndex = index;
      render();
      close(true);
      if (changed) {
        select.dispatchEvent(new Event("input", { bubbles: true }));
        select.dispatchEvent(new Event("change", { bubbles: true }));
      }
    }
    button.addEventListener("click", () => (list.hidden ? open() : close()));
    button.addEventListener("keydown", (event) => {
      if (event.key === "Tab") {
        close();
        return;
      }
      if (event.key === "Escape") {
        if (!list.hidden) {
          event.preventDefault();
          close(true);
        }
        return;
      }
      if (["Enter", " "].includes(event.key)) {
        event.preventDefault();
        if (list.hidden) open();
        else if (active >= 0) choose(active);
        return;
      }
      if (["ArrowDown", "ArrowUp", "Home", "End"].includes(event.key)) {
        event.preventDefault();
        const wasClosed = list.hidden;
        if (wasClosed) open();
        const entries = enabled();
        if (!entries.length) return;
        if (event.key === "Home") highlight(entries[0]);
        else if (event.key === "End") highlight(entries.at(-1));
        else if (!wasClosed) {
          const index = entries.indexOf(active),
            step = event.key === "ArrowDown" ? 1 : -1;
          highlight(entries[(index + step + entries.length) % entries.length]);
        }
        return;
      }
      if (
        event.key.length === 1 &&
        !event.ctrlKey &&
        !event.metaKey &&
        !event.altKey
      ) {
        event.preventDefault();
        typed = Date.now() - lastTyped < 700 ? typed + event.key : event.key;
        lastTyped = Date.now();
        if (list.hidden) open();
        const normalize = (value) =>
          value
            .normalize("NFD")
            .replace(/[\u0300-\u036f]/g, "")
            .toLocaleLowerCase("fr");
        const match = enabled().find((index) =>
          normalize(options[index].option.textContent).startsWith(
            normalize(typed),
          ),
        );
        if (match !== undefined) highlight(match);
      }
    });
    select.addEventListener("change", render);
    select.addEventListener("invalid", (event) => {
      event.preventDefault();
      wrapper.classList.add("has-error");
      button.setAttribute("aria-invalid", "true");
      button.setAttribute("aria-describedby", error.id);
      error.textContent = "Choisissez une valeur dans la liste.";
      error.hidden = false;
      button.focus();
      open();
    });
    render();
  }
  document.addEventListener("pointerdown", (event) => {
    if (opened && !event.target.closest(".select-control")) opened.close();
  });
  document.addEventListener("focusin", (event) => {
    if (opened && !event.target.closest(".select-control")) opened.close();
  });
  window.addEventListener("resize", () => opened?.close());
})();
