/* GŁÓWNY SKRYPT STRONY */
const menuToggle = document.querySelector(".menu-toggle");
const menuClose = document.querySelector(".menu-close");
const navPanel = document.querySelector(".nav-panel");
const navLinks = document.querySelectorAll(".nav-list a");
const hashLinks = document.querySelectorAll('a[href^="#"]');
const customSelects = document.querySelectorAll("[data-custom-select]");
const contactForm = document.querySelector(".contact-form");
const revealElements = document.querySelectorAll(".reveal-on-scroll");
const paginatedLists = document.querySelectorAll("[data-pagination]");
const projectGalleries = document.querySelectorAll("[data-project-gallery]");
const searchInputs = document.querySelectorAll(".search-form input[name='s']");
const cookieNotice = document.querySelector("[data-cookie-notice]");
const cookieNoticeAccept = document.querySelector("[data-cookie-notice-accept]");
const cookieNoticeReject = document.querySelector("[data-cookie-notice-reject]");
const scrollTopButton = document.querySelector("[data-scroll-top]");

/* USTAWIANIE STANU MENU MOBILNEGO */
const setMenuState = (isOpen) => {
    navPanel.classList.toggle("is-open", isOpen);
    document.body.classList.toggle("is-menu-open", isOpen);
    menuToggle.setAttribute("aria-expanded", String(isOpen));
    menuToggle.setAttribute("aria-label", isOpen ? "Zamknij menu" : "Otwórz menu");
};

/* POBIERANIE WARTOŚCI ZMIENNYCH CSS */
const getCssSize = (propertyName) => {
    const value = getComputedStyle(document.documentElement).getPropertyValue(propertyName);

    return parseFloat(value) || 0;
};

/* PRZEWIJANIE DO SEKCJI POD HEADEREM */
const scrollToSection = (targetSection) => {
    const sectionHeading = targetSection.querySelector("h2");
    const scrollTarget = sectionHeading || targetSection;
    const headerHeight = getCssSize("--header-height");
    const scrollGap = getCssSize("--section-scroll-gap");
    const targetPosition = scrollTarget.getBoundingClientRect().top + window.scrollY - headerHeight - scrollGap;

    window.scrollTo({
        top: targetPosition,
        behavior: "smooth"
    });
};

if (menuToggle && menuClose && navPanel) {
    /* OTWIERANIE I ZAMYKANIE MENU */
    menuToggle.addEventListener("click", () => setMenuState(!navPanel.classList.contains("is-open")));
    menuClose.addEventListener("click", () => setMenuState(false));

    /* ZAMYKANIE MENU PO KLIKNIĘCIU W LINK */
    navLinks.forEach((link) => {
        link.addEventListener("click", () => setMenuState(false));
    });

    /* ZAMYKANIE MENU KLAWISZEM ESCAPE */
    document.addEventListener("keydown", (event) => {
        if (event.key === "Escape" && navPanel.classList.contains("is-open")) {
            setMenuState(false);
        }
    });
}

/* OBSŁUGA WŁASNYCH LIST ROZWIJANYCH */
customSelects.forEach((customSelect) => {
    const selectInput = customSelect.querySelector("input");
    const selectButton = customSelect.querySelector(".custom-select__button");
    const selectValue = customSelect.querySelector(".custom-select__button span");
    const selectOptions = customSelect.querySelectorAll("[role='option']");

    selectButton.addEventListener("click", () => {
        const isOpen = customSelect.classList.toggle("is-open");

        selectButton.setAttribute("aria-expanded", String(isOpen));
    });

    selectOptions.forEach((option) => {
        option.addEventListener("click", () => {
            selectInput.value = option.dataset.value;
            selectValue.textContent = option.textContent;
            customSelect.classList.remove("is-open");
            selectButton.setAttribute("aria-expanded", "false");

            selectOptions.forEach((item) => {
                const isSelected = item === option;

                item.classList.toggle("is-selected", isSelected);
                item.setAttribute("aria-selected", String(isSelected));
            });
        });
    });
});

/* ZAMYKANIE LIST ROZWIJANYCH POZA POLEM */
document.addEventListener("click", (event) => {
    customSelects.forEach((customSelect) => {
        if (!customSelect.contains(event.target)) {
            customSelect.classList.remove("is-open");
            customSelect.querySelector(".custom-select__button").setAttribute("aria-expanded", "false");
        }
    });
});

/* ZAMYKANIE LIST ROZWIJANYCH KLAWISZEM ESCAPE */
document.addEventListener("keydown", (event) => {
    if (event.key !== "Escape") {
        return;
    }

    customSelects.forEach((customSelect) => {
        customSelect.classList.remove("is-open");
        customSelect.querySelector(".custom-select__button").setAttribute("aria-expanded", "false");
    });
});

/* WALIDACJA WŁASNEJ LISTY W FORMULARZU */
if (contactForm) {
    contactForm.addEventListener("submit", (event) => {
        const subjectInput = contactForm.querySelector("#contact-subject");
        const subjectSelect = contactForm.querySelector("[data-custom-select]");

        if (subjectInput && subjectSelect && !subjectInput.value) {
            event.preventDefault();
            subjectSelect.classList.add("is-open");
            subjectSelect.querySelector(".custom-select__button").setAttribute("aria-expanded", "true");
            subjectSelect.querySelector(".custom-select__button").focus();
        }
    });
}

/* UZUPEŁNIANIE POLA WYSZUKIWANIA Z ADRESU */
searchInputs.forEach((searchInput) => {
    const searchValue = new URLSearchParams(window.location.search).get("s");

    if (searchValue) {
        searchInput.value = searchValue;
    }
});

/* OBSŁUGA GALERII PROJEKTU */
if (projectGalleries.length && window.Fancybox) {
    window.Fancybox.bind("[data-fancybox]", {});
}

projectGalleries.forEach((gallery) => {
    const track = gallery.querySelector(".project-gallery__track");
    const slides = [...gallery.querySelectorAll(".project-gallery__slide")];
    const thumbnailsWrapper = gallery.querySelector(".project-gallery__thumbs");
    const thumbnails = [...gallery.querySelectorAll(".project-gallery__thumbs button")];
    const galleryItemsCount = Math.min(slides.length, thumbnails.length);
    let activeIndex = thumbnails.findIndex((thumbnail) => thumbnail.classList.contains("is-active"));
    let isMouseDown = false;
    let isThumbnailDragging = false;
    let dragStartX = 0;
    let dragStartScrollLeft = 0;
    let autoplay;

    if (!track || !thumbnailsWrapper || !galleryItemsCount) {
        return;
    }

    if (activeIndex < 0) {
        activeIndex = 0;
    }

    /* PRZEWIJANIE MINIATUREK BEZ ZMIANY POZYCJI STRONY */
    const scrollActiveThumbnail = (thumbnail) => {
        const thumbnailPosition = thumbnail.offsetLeft - ((thumbnailsWrapper.clientWidth - thumbnail.offsetWidth) / 2);

        thumbnailsWrapper.scrollTo({
            left: thumbnailPosition,
            behavior: "smooth"
        });
    };

    /* PRZEWIJANIE MINIATUREK MYSZKĄ NA DESKTOPIE */
    const startThumbnailsDrag = (event) => {
        if (!window.matchMedia("(pointer: fine)").matches) {
            return;
        }

        isMouseDown = true;
        isThumbnailDragging = false;
        dragStartX = event.pageX;
        dragStartScrollLeft = thumbnailsWrapper.scrollLeft;
        thumbnailsWrapper.classList.add("is-dragging");
    };

    const moveThumbnailsDrag = (event) => {
        if (!isMouseDown) {
            return;
        }

        const dragDistance = event.pageX - dragStartX;

        if (Math.abs(dragDistance) > 4) {
            isThumbnailDragging = true;
        }

        thumbnailsWrapper.scrollLeft = dragStartScrollLeft - dragDistance;
    };

    const stopThumbnailsDrag = () => {
        isMouseDown = false;
        thumbnailsWrapper.classList.remove("is-dragging");
    };

    /* PRZEWIJANIE MINIATUREK KÓŁKIEM MYSZY */
    const scrollThumbnailsByWheel = (event) => {
        if (!window.matchMedia("(pointer: fine)").matches) {
            return;
        }

        event.preventDefault();
        thumbnailsWrapper.scrollLeft += event.deltaY || event.deltaX;
    };

    /* PRZEŁĄCZANIE AKTYWNEGO SCREENA */
    const showProjectScreen = (screenIndex) => {
        const nextIndex = (screenIndex + galleryItemsCount) % galleryItemsCount;
        const nextThumbnail = thumbnails[nextIndex];

        activeIndex = nextIndex;
        track.style.transform = `translateX(-${activeIndex * 100}%)`;

        slides.forEach((slide, index) => {
            slide.classList.toggle("is-active", index === activeIndex);
        });

        thumbnails.forEach((thumbnail, index) => {
            thumbnail.classList.toggle("is-active", index === activeIndex);
        });

        scrollActiveThumbnail(nextThumbnail);
    };

    /* AUTOMATYCZNE PRZEWIJANIE SCREENÓW */
    const startProjectAutoplay = () => {
        clearInterval(autoplay);
        autoplay = setInterval(() => showProjectScreen(activeIndex + 1), 4000);
    };

    thumbnails.forEach((thumbnail, index) => {
        thumbnail.addEventListener("click", () => {
            if (isThumbnailDragging) {
                isThumbnailDragging = false;
                return;
            }

            showProjectScreen(index);
            startProjectAutoplay();
        });
    });

    thumbnailsWrapper.addEventListener("mousedown", startThumbnailsDrag);
    thumbnailsWrapper.addEventListener("mousemove", moveThumbnailsDrag);
    thumbnailsWrapper.addEventListener("mouseleave", stopThumbnailsDrag);
    thumbnailsWrapper.addEventListener("wheel", scrollThumbnailsByWheel, { passive: false });
    window.addEventListener("mouseup", stopThumbnailsDrag);

    showProjectScreen(activeIndex);
    startProjectAutoplay();
});

/* ANIMACJE ELEMENTÓW PODCZAS PRZEWIJANIA */
if (revealElements.length) {
    const revealObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) {
                return;
            }

            entry.target.classList.add("is-visible");
            observer.unobserve(entry.target);
        });
    }, {
        rootMargin: "0px 0px -12% 0px",
        threshold: 0.16
    });

    revealElements.forEach((element) => revealObserver.observe(element));
}

/* PAGINACJA WPISÓW */
paginatedLists.forEach((list) => {
    const items = [...list.querySelectorAll("[data-pagination-item]")];
    const itemsPerPage = Number(list.dataset.itemsPerPage) || 4;
    const paginationWrapper = list.parentElement;
    const pagination = paginationWrapper.querySelector("[data-pagination-controls]");
    const filterControls = paginationWrapper.parentElement.querySelector("[data-filter-controls]");
    const searchQuery = list.hasAttribute("data-search-results") ? (new URLSearchParams(window.location.search).get("s") || "").trim().toLowerCase() : "";
    const searchQueryLabel = document.querySelector("[data-search-query]");
    const searchEmpty = paginationWrapper.querySelector("[data-search-empty]");
    let currentFilter = "all";

    if (!pagination) {
        return;
    }

    const getFilteredItems = () => {
        let filteredItems = items;

        if (currentFilter !== "all") {
            filteredItems = filteredItems.filter((item) => item.dataset.category === currentFilter);
        }

        if (searchQuery) {
            filteredItems = filteredItems.filter((item) => item.textContent.toLowerCase().includes(searchQuery));
        }

        return filteredItems;
    };

    const showPage = (pageNumber) => {
        const filteredItems = getFilteredItems();
        const startIndex = (pageNumber - 1) * itemsPerPage;
        const endIndex = startIndex + itemsPerPage;

        items.forEach((item) => {
            item.hidden = true;
            item.style.display = "none";
        });

        filteredItems.forEach((item, index) => {
            const isHidden = index < startIndex || index >= endIndex;

            item.hidden = isHidden;
            item.style.display = isHidden ? "none" : "";
        });

        pagination.querySelectorAll("button").forEach((button) => {
            const isActive = Number(button.dataset.page) === pageNumber;

            button.classList.toggle("is-active", isActive);
            button.setAttribute("aria-current", isActive ? "page" : "false");
        });

        if (searchEmpty) {
            searchEmpty.hidden = filteredItems.length > 0;
        }
    };

    const renderPagination = () => {
        const filteredItems = getFilteredItems();
        const pagesCount = Math.max(1, Math.ceil(filteredItems.length / itemsPerPage));

        pagination.innerHTML = "";

        for (let pageNumber = 1; pageNumber <= pagesCount; pageNumber++) {
            const button = document.createElement("button");

            button.type = "button";
            button.textContent = pageNumber;
            button.dataset.page = pageNumber;
            button.setAttribute("aria-label", `Strona ${pageNumber}`);
            button.addEventListener("click", () => showPage(pageNumber));

            pagination.append(button);
        }

        showPage(1);
    };

    if (searchQueryLabel) {
        searchQueryLabel.textContent = searchQuery || "wszystkie wpisy";
    }

    if (filterControls) {
        filterControls.querySelectorAll("[data-filter]").forEach((button) => {
            button.addEventListener("click", () => {
                currentFilter = button.dataset.filter;

                filterControls.querySelectorAll("[data-filter]").forEach((filterButton) => {
                    const isActive = filterButton === button;

                    filterButton.classList.toggle("is-active", isActive);
                    filterButton.setAttribute("aria-pressed", String(isActive));
                });

                renderPagination();
            });
        });
    }

    renderPagination();
});

/* OBSŁUGA LINKÓW DO SEKCJI */
hashLinks.forEach((link) => {
    link.addEventListener("click", (event) => {
        const targetSection = document.querySelector(link.hash);

        if (!targetSection) {
            return;
        }

        event.preventDefault();
        scrollToSection(targetSection);
        setActiveSection(targetSection.id);
        history.pushState(null, "", link.hash);
    });
});

/* KOREKTA POZYCJI PO WEJŚCIU Z HASHEM W ADRESIE */
window.addEventListener("load", () => {
    if (!window.location.hash) {
        return;
    }

    const targetSection = document.querySelector(window.location.hash);

    if (targetSection) {
        scrollToSection(targetSection);
    }
});

const sectionLinks = [...navLinks].filter((link) => link.hash && document.querySelector(link.hash));
const pageSections = sectionLinks.map((link) => document.querySelector(link.hash));

/* USTAWIANIE AKTYWNEGO LINKU NAWIGACJI */
const setActiveSection = (sectionId) => {
    sectionLinks.forEach((link) => {
        const isActive = link.hash === `#${sectionId}`;

        link.classList.toggle("is-active", isActive);

        if (isActive) {
            link.setAttribute("aria-current", "page");
        } else {
            link.removeAttribute("aria-current");
        }
    });
};

/* SPRAWDZANIE AKTYWNEJ SEKCJI POD HEADEREM */
const updateActiveSection = () => {
    const headerHeight = getCssSize("--header-height");
    const scrollGap = getCssSize("--section-scroll-gap");
    const activationLine = headerHeight + scrollGap + 1;
    let activeSection = pageSections[0];

    pageSections.forEach((section) => {
        const sectionRect = section.getBoundingClientRect();

        if (sectionRect.top <= activationLine) {
            activeSection = section;
        }
    });

    if (activeSection) {
        setActiveSection(activeSection.id);
    }
};

if (pageSections.length) {
    updateActiveSection();
    window.addEventListener("scroll", updateActiveSection, { passive: true });
    window.addEventListener("resize", updateActiveSection);
}

/* OBSŁUGA KOMUNIKATU O CIASTECZKACH */
if (cookieNotice && cookieNoticeAccept && cookieNoticeReject) {
    const cookieNoticeStorageKey = "mariuszkowalCookieConsentDecision";
    const legacyCookieNoticeStorageKey = "mariuszkowalCookieNoticeAccepted";
    const readCookieNoticeDecision = () => {
        try {
            return localStorage.getItem(cookieNoticeStorageKey) || localStorage.getItem(legacyCookieNoticeStorageKey);
        } catch (error) {
            return "";
        }
    };
    const writeCookieNoticeDecision = (decision) => {
        try {
            localStorage.setItem(cookieNoticeStorageKey, decision);
            localStorage.removeItem(legacyCookieNoticeStorageKey);
        } catch (error) {
            document.documentElement.dataset.cookieConsentStorage = "unavailable";
        }
    };
    const cookieNoticeDecision = readCookieNoticeDecision();
    const normalizedCookieNoticeDecision = cookieNoticeDecision === "true" ? "accepted" : cookieNoticeDecision;
    const hasCookieNoticeDecision = ["accepted", "rejected"].includes(normalizedCookieNoticeDecision);

    cookieNotice.hidden = hasCookieNoticeDecision;

    const saveCookieNoticeDecision = (decision) => {
        writeCookieNoticeDecision(decision);
        document.documentElement.dataset.cookieConsent = decision;
        cookieNotice.hidden = true;
        window.dispatchEvent(new CustomEvent("mariuszkowalCookieConsentChange", {
            detail: {
                decision
            }
        }));
    };

    if (hasCookieNoticeDecision) {
        document.documentElement.dataset.cookieConsent = normalizedCookieNoticeDecision;
    }

    cookieNoticeAccept.addEventListener("click", () => {
        saveCookieNoticeDecision("accepted");
    });

    cookieNoticeReject.addEventListener("click", () => {
        saveCookieNoticeDecision("rejected");
    });
}

/* PRZYCISK PRZEWIJANIA DO GÓRY */
if (scrollTopButton) {
    const toggleScrollTopButton = () => {
        scrollTopButton.hidden = window.scrollY < 600;
    };

    scrollTopButton.addEventListener("click", () => {
        window.scrollTo({
            top: 0,
            behavior: "smooth"
        });
    });

    toggleScrollTopButton();
    window.addEventListener("scroll", toggleScrollTopButton, { passive: true });
}
