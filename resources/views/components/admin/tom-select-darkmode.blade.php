{{-- Tom Select dark mode override for CoreUI --}}
<style>
    /* Tom Select wrapper */
    .ts-wrapper.single .ts-control,
    .ts-wrapper.multi .ts-control {
        background-color: var(--cui-input-bg, var(--cui-body-bg));
        border-color: var(--cui-input-border-color, var(--cui-border-color));
        color: var(--cui-input-color, var(--cui-body-color));
    }

    .ts-wrapper.single .ts-control:focus,
    .ts-wrapper.multi .ts-control:focus,
    .ts-wrapper.single.focus .ts-control,
    .ts-wrapper.multi.focus .ts-control {
        border-color: var(--cui-input-focus-border-color, #5856d6);
        box-shadow: 0 0 0 0.25rem rgba(88, 86, 214, 0.25);
    }

    /* Dropdown */
    .ts-dropdown {
        background-color: var(--cui-dropdown-bg, var(--cui-body-bg));
        border-color: var(--cui-dropdown-border-color, var(--cui-border-color));
        color: var(--cui-dropdown-color, var(--cui-body-color));
        z-index: 1050;
    }

    .ts-dropdown .option {
        color: var(--cui-dropdown-link-color, var(--cui-body-color));
    }

    .ts-dropdown .option:hover,
    .ts-dropdown .active {
        background-color: var(--cui-dropdown-link-hover-bg, rgba(88, 86, 214, 0.1));
        color: var(--cui-dropdown-link-hover-color, var(--cui-body-color));
    }

    .ts-dropdown .option.active {
        background-color: rgba(88, 86, 214, 0.2);
    }

    /* Tags (selected items in multi-select) */
    .ts-wrapper.multi .ts-control > .item {
        background-color: var(--cui-primary, #5856d6);
        color: #fff;
        border: none;
        border-radius: 4px;
        padding: 2px 8px;
    }

    /* Placeholder & input */
    .ts-control input,
    .ts-control > input {
        color: var(--cui-input-color, var(--cui-body-color)) !important;
    }

    .ts-control input::placeholder {
        color: var(--cui-input-placeholder-color, var(--cui-secondary-color)) !important;
    }

    /* No results text */
    .ts-dropdown .no-results {
        color: var(--cui-secondary-color);
    }
</style>
