/* Back-office interactions: no HTML assembled from user data or API responses. */
(() => {
  "use strict";
  const $ = (s, root = document) => root.querySelector(s);
  const $$ = (s, root = document) => [...root.querySelectorAll(s)];
  const normalize = (value) =>
    value
      .normalize("NFD")
      .replace(/[\u0300-\u036f]/g, "")
      .toLocaleLowerCase("fr")
      .trim();
  $$("[data-toggle-password]").forEach((button) =>
    button.addEventListener("click", () => {
      const input = document.getElementById(button.dataset.togglePassword);
      const visible = input.type === "password";
      input.type = visible ? "text" : "password";
      button.textContent = visible ? "Masquer" : "Afficher";
      button.setAttribute("aria-pressed", String(visible));
    }),
  );
  const search = $("#reference-search");
  if (search) {
    const domain = $("#domain-filter"),
      status = $("#status-filter"),
      cards = $$(".project-card");
    const filter = () => {
      let count = 0;
      const query = normalize(search.value);
      cards.forEach((card) => {
        const visible =
          normalize(card.dataset.search).includes(query) &&
          (!domain.value || card.dataset.domain === domain.value) &&
          (!status.value || card.dataset.status === status.value);
        card.hidden = !visible;
        if (visible) count++;
      });
      $("#empty-results").hidden = count > 0;
      $("#filter-count").textContent =
        `${count} référence${count > 1 ? "s" : ""} affichée${count > 1 ? "s" : ""}`;
    };
    [search, domain, status].forEach((field) =>
      field.addEventListener("input", filter),
    );
    const dialog = $("#delete-dialog");
    $$("[data-delete-id]").forEach((button) =>
      button.addEventListener("click", () => {
        $("[name=delete_id]", dialog).value = button.dataset.deleteId;
        $("[name=version]", dialog).value = button.dataset.deleteVersion;
        $("#delete-project-title").textContent = button.dataset.deleteTitle;
        $("#confirmation").value = "";
        dialog.showModal();
      }),
    );
    $("[data-close-dialog]", dialog).addEventListener("click", () =>
      dialog.close(),
    );
    dialog.addEventListener("click", (event) => {
      if (
        event.target === dialog &&
        (event.clientX < dialog.getBoundingClientRect().left ||
          event.clientX > dialog.getBoundingClientRect().right)
      )
        dialog.close();
    });
  }
  const form = $("[data-reference-editor]");
  if (!form) return;
  let dirty = false,
    submitting = false,
    saveAttempt = 0;
  const markDirty = () => {
    dirty = true;
    $("#save-status").textContent = "Modifications non enregistrées";
    $("#save-indicator").classList.add("is-dirty");
  };
  form.addEventListener("input", markDirty);
  form.addEventListener("change", markDirty);
  window.addEventListener("beforeunload", (event) => {
    if (dirty && !submitting) {
      event.preventDefault();
      event.returnValue = "";
    }
  });
  const discardDialog = $("#discard-dialog");
  let pendingNavigation = "/admin/admin.php";
  document.addEventListener("click", (event) => {
    const link = event.target.closest("a[href]");
    if (
      !link ||
      event.defaultPrevented ||
      event.button !== 0 ||
      event.ctrlKey ||
      event.metaKey ||
      event.altKey ||
      event.shiftKey ||
      link.target === "_blank"
    )
      return;
    const url = new URL(link.href, location.href);
    if (
      url.origin !== location.origin ||
      (url.pathname === location.pathname &&
        url.search === location.search &&
        url.hash)
    )
      return;
    if (submitting) {
      event.preventDefault();
      return;
    }
    if (!dirty) return;
    event.preventDefault();
    // A pending authentication preflight must not publish after the user decides to leave.
    saveAttempt++;
    $("#save-status").textContent = "Modifications non enregistrées";
    pendingNavigation = url.href;
    if (!discardDialog.open) discardDialog.showModal();
  });
  $("[data-keep-editing]", discardDialog).addEventListener("click", () =>
    discardDialog.close(),
  );
  $("[data-discard-edit]", discardDialog).addEventListener("click", () => {
    dirty = false;
    saveAttempt++;
    discardDialog.close();
    // Replace the abandoned editor so browser Back cannot restore its discarded draft.
    location.replace(pendingNavigation);
  });
  const title = $("#titre"),
    city = $("#city"),
    postal = $("#postal_code"),
    status = $("#statut"),
    domain = $("#domaine");
  const previewPlaceholder =
    $("#preview-image").firstElementChild.cloneNode(true);
  const gallery = $("#photo-gallery"),
    input = $("#image-files"),
    zone = $("#upload-zone"),
    uploadErrors = $("#upload-errors");
  let queue = [],
    nextFile = 0,
    dragging = null;
  const undoStack = [],
    urls = new Set();
  const preview = () => {
    $("#preview-title").textContent =
      title.value.trim() || "Le nom de votre projet";
    $("#preview-location").textContent = city.value.trim()
      ? `${city.value.trim()}${postal.value ? ` (${postal.value})` : ""}`
      : "La ville du projet";
    $("#preview-status").textContent = status.value
      ? status.selectedOptions[0].textContent
      : "Statut à renseigner";
    $("#preview-status").className = `status-badge status-${status.value}`;
    $("#preview-domain").textContent = domain.value
      ? domain.selectedOptions[0].textContent
      : "Domaine du projet";
    $("#description-count").textContent =
      `${$("#description").value.length.toLocaleString("fr-FR")} / 10 000`;
    const cover = $("img", gallery);
    const container = $("#preview-image");
    if (cover) {
      let image = $("img", container);
      if (!image) {
        image = document.createElement("img");
        image.alt = "Aperçu de la couverture";
        container.replaceChildren(image);
      }
      image.src = cover.src;
    } else container.replaceChildren(previewPlaceholder.cloneNode(true));
  };
  form.addEventListener("input", preview);
  form.addEventListener("change", preview);
  const showUploadError = (message) => {
    uploadErrors.textContent = message;
    uploadErrors.hidden = !message;
  };
  const sync = () => {
    const transfer = new DataTransfer();
    queue.forEach((item) => transfer.items.add(item.file));
    input.files = transfer.files;
    const tiles = [...gallery.children];
    const order = tiles.map((tile, index) => {
      $(".cover-label", tile).hidden = index !== 0;
      $(".photo-position", tile).textContent = `Photo ${index + 1}`;
      $("img", tile).alt = `Photo ${index + 1} de la référence`;
      $('[data-photo-move="-1"]', tile).disabled = index === 0;
      $('[data-photo-move="1"]', tile).disabled = index === tiles.length - 1;
      return tile.dataset.photoKey.startsWith("existing:")
        ? tile.dataset.photoKey
        : `new:${queue.findIndex((item) => item.key === tile.dataset.photoKey)}`;
    });
    $("#photo-order").value = JSON.stringify(order);
    $("#photo-total").textContent = String(tiles.length);
    preview();
  };
  const notifyGallery = (message) => {
    const output = $("#gallery-status");
    output.replaceChildren(document.createTextNode(message + " "));
    if (undoStack.length) {
      const button = document.createElement("button");
      button.type = "button";
      button.className = "undo-button";
      button.textContent = "Annuler le dernier retrait";
      button.addEventListener("click", undo);
      output.append(button);
    }
  };
  const undo = () => {
    if (gallery.children.length >= 20) {
      showUploadError("La galerie contient déjà 20 photos.");
      return;
    }
    const removed = undoStack.pop();
    if (!removed) return;
    if (removed.file) queue.push(removed.file);
    else
      $(
        `[data-deleted-id="${removed.tile.dataset.photoKey.split(":")[1]}"]`,
      )?.remove();
    gallery.insertBefore(removed.tile, gallery.children[removed.index] || null);
    markDirty();
    sync();
    notifyGallery("Photo rétablie.");
  };
  const tileFor = (item) => {
    const tile = document.createElement("div");
    tile.className = "photo-tile";
    tile.dataset.photoKey = item.key;
    tile.draggable = true;
    const imageBox = document.createElement("div");
    imageBox.className = "photo-tile-image";
    const image = document.createElement("img");
    const url = URL.createObjectURL(item.file);
    urls.add(url);
    image.src = url;
    const label = document.createElement("span");
    label.className = "cover-label";
    label.textContent = "Couverture";
    imageBox.append(image, label);
    const footer = document.createElement("div");
    footer.className = "photo-tile-footer";
    const number = document.createElement("span");
    number.className = "photo-position";
    const actions = document.createElement("div");
    [
      ["←", "-1", "Déplacer cette photo vers le début"],
      ["→", "1", "Déplacer cette photo vers la fin"],
      ["×", null, "Retirer cette photo"],
    ].forEach(([text, direction, aria]) => {
      const button = document.createElement("button");
      button.type = "button";
      button.className = `mini-button${direction ? "" : " danger"}`;
      button.textContent = text;
      button.setAttribute("aria-label", aria);
      if (direction) button.dataset.photoMove = direction;
      else button.dataset.photoRemove = "";
      actions.append(button);
    });
    footer.append(number, actions);
    tile.append(imageBox, footer);
    return tile;
  };
  const addFiles = (files) => {
    const batch = [...files];
    if (!batch.length) return;
    let error = "";
    if (gallery.children.length + batch.length > 20)
      error = "Une référence peut contenir au maximum 20 photos.";
    else if (
      batch.some(
        (file) =>
          !["image/jpeg", "image/png", "image/webp"].includes(file.type) ||
          !/\.(jpe?g|png|webp)$/i.test(file.name),
      )
    )
      error = "Choisissez des photos JPG, PNG ou WebP.";
    else if (batch.some((file) => file.size > 15 * 1024 * 1024 || !file.size))
      error = "Chaque photo doit peser entre 1 octet et 15 Mo.";
    else if (
      [...queue.map((item) => item.file), ...batch].reduce(
        (sum, file) => sum + file.size,
        0,
      ) >
      60 * 1024 * 1024
    )
      error =
        "Ajoutez au maximum 60 Mo de nouvelles photos par enregistrement.";
    if (error) {
      showUploadError(error);
      sync();
      return;
    }
    showUploadError("");
    batch.forEach((file) => {
      const item = { file, key: `queued:${nextFile++}` };
      queue.push(item);
      gallery.append(tileFor(item));
    });
    markDirty();
    sync();
    notifyGallery(
      `${batch.length} photo${batch.length > 1 ? "s" : ""} ajoutée${batch.length > 1 ? "s" : ""}.`,
    );
  };
  input.addEventListener("change", () => addFiles(input.files));
  ["dragenter", "dragover"].forEach((type) =>
    zone.addEventListener(type, (event) => {
      if ([...event.dataTransfer.types].includes("Files")) {
        event.preventDefault();
        zone.classList.add("is-dragging");
      }
    }),
  );
  zone.addEventListener("dragleave", (event) => {
    if (!zone.contains(event.relatedTarget))
      zone.classList.remove("is-dragging");
  });
  zone.addEventListener("drop", (event) => {
    event.preventDefault();
    zone.classList.remove("is-dragging");
    addFiles(event.dataTransfer.files);
  });
  gallery.addEventListener("click", (event) => {
    const button = event.target.closest("button");
    if (!button) return;
    const tile = button.closest(".photo-tile");
    if (button.hasAttribute("data-photo-remove")) {
      const index = [...gallery.children].indexOf(tile);
      const file = queue.find((item) => item.key === tile.dataset.photoKey);
      undoStack.push({ tile, index, file });
      if (file) queue = queue.filter((item) => item !== file);
      else {
        const hidden = document.createElement("input");
        hidden.type = "hidden";
        hidden.name = "delete_photos[]";
        hidden.value = tile.dataset.photoKey.split(":")[1];
        hidden.dataset.deletedId = hidden.value;
        $("#deleted-photos").append(hidden);
      }
      tile.remove();
      notifyGallery(
        "Photo retirée. Le retrait sera appliqué à l’enregistrement.",
      );
    } else if (button.dataset.photoMove) {
      const direction = Number(button.dataset.photoMove);
      const sibling =
        direction < 0 ? tile.previousElementSibling : tile.nextElementSibling;
      if (sibling) {
        if (direction < 0) sibling.before(tile);
        else sibling.after(tile);
      }
      notifyGallery("L’ordre des photos a été mis à jour.");
    }
    markDirty();
    sync();
  });
  gallery.addEventListener("dragstart", (event) => {
    dragging = event.target.closest(".photo-tile");
    if (!dragging) return;
    event.dataTransfer.effectAllowed = "move";
    event.dataTransfer.setData("text/plain", dragging.dataset.photoKey);
    dragging.classList.add("is-dragging");
  });
  gallery.addEventListener("dragover", (event) => {
    if (dragging) event.preventDefault();
  });
  gallery.addEventListener("drop", (event) => {
    if (!dragging) return;
    event.preventDefault();
    const target = event.target.closest(".photo-tile");
    if (target && target !== dragging) {
      const tiles = [...gallery.children];
      if (tiles.indexOf(dragging) < tiles.indexOf(target))
        target.after(dragging);
      else target.before(dragging);
      markDirty();
      sync();
      notifyGallery("L’ordre des photos a été mis à jour.");
    }
  });
  gallery.addEventListener("dragend", () => {
    dragging?.classList.remove("is-dragging");
    dragging = null;
  });
  let checkingSession = false,
    submissionApproved = false;
  form.addEventListener("submit", async (event) => {
    if (discardDialog.open) {
      event.preventDefault();
      return;
    }
    if (submitting) {
      event.preventDefault();
      return;
    }
    if (submissionApproved) {
      submissionApproved = false;
      submitting = true;
      $("#save-status").textContent =
        "Enregistrement et optimisation des photos…";
      setTimeout(() => {
        $$("button[type=submit]", form).forEach((button) => {
          button.disabled = true;
        });
        form.classList.add("is-submitting");
      }, 0);
      return;
    }
    event.preventDefault();
    if (checkingSession) return;
    if (!gallery.children.length) {
      showUploadError(
        "Ajoutez au moins une photo pour publier cette référence.",
      );
      $("#photos").scrollIntoView({ behavior: "smooth", block: "start" });
      $("#image-files").focus();
      return;
    }
    sync();
    checkingSession = true;
    const attempt = ++saveAttempt;
    const submitter =
      event.submitter || $("button[name=intent][value=save]", form);
    let warning = $("#session-warning");
    if (warning) warning.hidden = true;
    const timeout = new AbortController();
    const timeoutId = setTimeout(() => timeout.abort(), 8000);
    try {
      const params = new URLSearchParams({
        reference: form.dataset.referenceId,
        submission: $("[name=submission]", form).value,
      });
      const response = await fetch(`/admin/session.php?${params}`, {
        signal: timeout.signal,
        headers: { Accept: "application/json" },
      });
      if (!response.ok) throw new Error("Session unavailable");
      const state = await response.json();
      if (
        !state.authenticated ||
        typeof state.csrf !== "string" ||
        typeof state.submission !== "string"
      )
        throw new Error("Session unavailable");
      if (attempt !== saveAttempt) return;
      $("[name=csrf]", form).value = state.csrf;
      $("[name=submission]", form).value = state.submission;
      submissionApproved = true;
      form.requestSubmit(submitter);
      submissionApproved = false;
    } catch (error) {
      if (attempt !== saveAttempt) return;
      if (!warning) {
        warning = document.createElement("div");
        warning.id = "session-warning";
        warning.className = "notice error";
        warning.setAttribute("role", "alert");
        warning.tabIndex = -1;
        form.before(warning);
      }
      const text = document.createElement("span");
      text.textContent =
        "La connexion n’a pas pu être vérifiée. Vos champs et vos photos sont conservés dans cette page. ";
      const link = document.createElement("a");
      link.href = "/admin/login.php";
      link.target = "_blank";
      link.rel = "noopener";
      link.textContent =
        "Reconnectez-vous dans un nouvel onglet, puis réessayez d’enregistrer.";
      text.append(link);
      warning.replaceChildren(text);
      warning.hidden = false;
      warning.focus();
      $("#save-status").textContent = "Saisie conservée · connexion à vérifier";
    } finally {
      clearTimeout(timeoutId);
      checkingSession = false;
    }
  });
  document.addEventListener("keydown", (event) => {
    if ((event.ctrlKey || event.metaKey) && event.key === "Enter") {
      event.preventDefault();
      if (discardDialog.open) return;
      form.requestSubmit($("button[name=intent][value=save]", form));
    }
  });
  window.addEventListener("pageshow", (event) => {
    if (event.persisted) {
      submitting = false;
      form.classList.remove("is-submitting");
      $$("button[type=submit]", form).forEach((button) => {
        button.disabled = false;
      });
      if (dirty)
        $("#save-status").textContent = "Modifications non enregistrées";
    }
  });
  // Official French commune service. Multiple postal codes/communes require an explicit choice.
  let timer = null,
    controller = null,
    sequence = 0,
    selected = null;
  const results = $("#location-results"),
    locationHelp = $("#location-help");
  const choose = (item) => {
    city.value = item.city;
    postal.value = item.postal;
    selected = item;
    results.hidden = true;
    results.replaceChildren();
    locationHelp.textContent = `${item.city} · ${item.postal} — commune sélectionnée`;
    markDirty();
    preview();
  };
  const lookup = async (field) => {
    const current = ++sequence;
    controller?.abort();
    controller = new AbortController();
    results.replaceChildren();
    results.hidden = true;
    const value = field.value.trim();
    if (
      (field === postal && !/^\d{5}$/.test(value)) ||
      (field === city && value.length < 2)
    ) {
      locationHelp.textContent =
        "Saisissez une ville ou un code postal à 5 chiffres.";
      return;
    }
    locationHelp.textContent = "Recherche des communes…";
    const url = new URL("https://geo.api.gouv.fr/communes");
    url.search = new URLSearchParams({
      [field === postal ? "codePostal" : "nom"]: value,
      fields: "nom,codesPostaux,codeDepartement,code",
      boost: "population",
      limit: "8",
    }).toString();
    const signal = controller.signal;
    const timeout = setTimeout(
      () => controller?.signal === signal && controller.abort(),
      6000,
    );
    try {
      const response = await fetch(url, { signal });
      if (!response.ok) throw new Error("Lookup unavailable");
      const data = await response.json();
      if (current !== sequence) return;
      const options = [];
      for (const item of Array.isArray(data) ? data : []) {
        if (typeof item.nom !== "string" || !Array.isArray(item.codesPostaux))
          continue;
        for (const code of item.codesPostaux) {
          if (
            typeof code !== "string" ||
            !/^\d{5}$/.test(code) ||
            (field === postal && code !== value)
          )
            continue;
          options.push({
            city: item.nom,
            postal: code,
            department: item.codeDepartement || "",
          });
        }
      }
      let unique = options
        .filter(
          (item, index) =>
            options.findIndex(
              (other) =>
                other.city === item.city &&
                other.postal === item.postal &&
                other.department === item.department,
            ) === index,
        )
        .slice(0, 24);
      if (field === city) {
        const exact = unique.filter(
          (item) => normalize(item.city) === normalize(value),
        );
        if (exact.length) unique = exact;
      }
      if (
        unique.length === 1 &&
        (field === postal || normalize(unique[0].city) === normalize(value))
      ) {
        choose(unique[0]);
        return;
      }
      if (!unique.length) {
        locationHelp.textContent =
          "Aucune commune trouvée. Vous pouvez saisir la ville et le code postal manuellement.";
        return;
      }
      unique.forEach((item) => {
        const button = document.createElement("button");
        button.type = "button";
        const name = document.createElement("span");
        name.textContent = item.city;
        const code = document.createElement("small");
        code.textContent = `${item.postal}${item.department ? ` · dép. ${item.department}` : ""}`;
        button.append(name, code);
        button.addEventListener("click", () => choose(item));
        results.append(button);
      });
      locationHelp.textContent =
        "Plusieurs possibilités : choisissez la bonne commune et son code postal.";
      results.hidden = false;
    } catch (error) {
      if (current === sequence)
        locationHelp.textContent =
          "Recherche indisponible. Saisissez la ville et le code postal manuellement.";
    } finally {
      clearTimeout(timeout);
    }
  };
  [city, postal].forEach((field) => {
    field.addEventListener("input", () => {
      clearTimeout(timer);
      sequence++;
      controller?.abort();
      results.hidden = true;
      if (
        selected &&
        field.value !== (field === city ? selected.city : selected.postal)
      ) {
        (field === city ? postal : city).value = "";
        selected = null;
        preview();
      }
      timer = setTimeout(() => lookup(field), 300);
    });
    field.addEventListener("keydown", (event) => {
      if (event.key === "ArrowDown" && !results.hidden) {
        event.preventDefault();
        $("button", results)?.focus();
      }
      if (event.key === "Escape") results.hidden = true;
    });
  });
  results.addEventListener("keydown", (event) => {
    const buttons = $$("button", results);
    const index = buttons.indexOf(document.activeElement);
    if (event.key === "ArrowDown") {
      event.preventDefault();
      buttons[(index + 1) % buttons.length]?.focus();
    }
    if (event.key === "ArrowUp") {
      event.preventDefault();
      buttons[(index - 1 + buttons.length) % buttons.length]?.focus();
    }
    if (event.key === "Escape") {
      results.hidden = true;
      city.focus();
    }
  });
  const amount = $("#montant");
  const formatAmount = () => {
    const raw = amount.value.replace(/[\s€]/g, "").replace(",", ".");
    if (/^\d{1,8}(?:\.\d{1,2})?$/.test(raw))
      amount.value = new Intl.NumberFormat("fr-FR", {
        maximumFractionDigits: 2,
      }).format(Number(raw));
  };
  amount.addEventListener("blur", formatAmount);
  formatAmount();
  const errors = $("#form-errors");
  if (errors) errors.focus();
  sync();
})();
