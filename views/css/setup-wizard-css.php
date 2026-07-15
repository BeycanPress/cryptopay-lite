<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<style>
    .cpl-wizard {
        max-width: 780px;
    }
    .cpl-wizard-head {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 20px 0;
    }
    .cpl-wizard-head h1 {
        margin: 0;
        padding: 0;
        font-size: 23px;
    }
    .cpl-steps {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin: 0 0 18px;
        padding: 0;
        list-style: none;
        counter-reset: none;
    }
    .cpl-steps li {
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 0;
        padding: 8px 14px 8px 8px;
        border-radius: 999px;
        background: #f0f0f1;
        color: #646970;
        font-size: 13px;
        line-height: 1;
    }
    .cpl-steps li.is-current {
        background: #2271b1;
        color: #fff;
    }
    .cpl-steps li.is-done {
        background: #edfaef;
        color: #1e7b34;
    }
    .cpl-step-num {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        background: rgba(0, 0, 0, 0.08);
        font-weight: 600;
    }
    .cpl-steps li.is-current .cpl-step-num {
        background: rgba(255, 255, 255, 0.25);
    }
    .cpl-wizard-box {
        padding: 26px 28px;
        border: 1px solid #dcdcde;
        border-radius: 6px;
        background: #fff;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
    }
    .cpl-wizard-box h2 {
        margin: 0 0 8px;
        font-size: 20px;
    }
    .cpl-lead {
        max-width: 62ch;
        margin: 0 0 20px;
        color: #50575e;
        font-size: 14px;
    }
    .cpl-note {
        max-width: 62ch;
        margin: 14px 0 0;
        color: #646970;
        font-size: 13px;
    }
    .cpl-input {
        width: 100%;
        max-width: 520px;
        padding: 10px 12px;
        font-family: Menlo, Consolas, monospace;
        font-size: 14px;
    }
    .cpl-choices {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(210px, 1fr));
        gap: 6px;
        margin: 0;
        padding: 0;
        border: 0;
    }
    .cpl-choice {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 11px 13px;
        border: 1px solid #dcdcde;
        border-radius: 5px;
        cursor: pointer;
    }
    .cpl-choice:hover {
        border-color: #2271b1;
    }
    .cpl-actions {
        display: flex;
        align-items: center;
        gap: 12px;
        margin: 26px 0 0;
    }
    .cpl-skip {
        color: #646970;
        text-decoration: underline;
        background: none;
        border: none;
        cursor: pointer;
    }
    .cpl-status {
        margin: 18px 0 0;
        padding: 0;
        list-style: none;
    }
    .cpl-status li {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin: 0;
        padding: 11px 0;
        border-top: 1px solid #f0f0f1;
        font-size: 13px;
    }
    .cpl-status-icon {
        flex: 0 0 auto;
        width: 9px;
        height: 9px;
        margin-top: 5px;
        border-radius: 50%;
    }
    .cpl-status-ok .cpl-status-icon {
        background: #00a32a;
    }
    .cpl-status-error .cpl-status-icon {
        background: #d63638;
    }
    .cpl-status-warning .cpl-status-icon {
        background: #dba617;
    }
    .cpl-status-text {
        max-width: 70ch;
    }
</style>
