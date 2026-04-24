/**
 * Feature: register-form-advanced-validation, Property 12: Submit button chỉ disable sau khi bấm
 * Validates: Requirements 9.1, 9.5, 10.2
 */
import { describe, it, beforeEach, expect } from 'vitest';
import fc from 'fast-check';
import { RegisterValidator } from '../auth.js';

describe('Feature: register-form-advanced-validation, Property 12: Submit button chỉ disable sau khi bấm', () => {
  beforeEach(() => {
    document.body.innerHTML = `
      <form id="testForm" action="/register">
        <div class="relative group"><input id="ho_ten" /></div>
        <div class="relative group"><input id="email" /></div>
        <div class="relative group"><input id="so_dien_thoai" /></div>
        <div class="relative group"><input id="password" type="password" /></div>
        <div class="relative group"><input id="password_confirmation" type="password" /></div>
        <label><input type="checkbox" id="agreeTerms" /><div></div></label>
        <button type="submit" id="submitBtn">
          <span class="btn-text">Bắt đầu hành trình ngay</span>
          <div class="btn-loader hidden"></div>
        </button>
      </form>
    `;
  });

  it('submit button is enabled before any submit attempt regardless of form state', () => {
    fc.assert(
      fc.property(
        fc.string(),
        fc.string(),
        (name, email) => {
          const validator = new RegisterValidator('testForm');
          validator.init();

          // Set some field values (may be valid or invalid)
          const hoTenInput = document.getElementById('ho_ten');
          const emailInput = document.getElementById('email');
          if (hoTenInput) hoTenInput.value = name;
          if (emailInput) emailInput.value = email;

          // Button should be enabled before submit
          const submitBtn = document.getElementById('submitBtn');
          return submitBtn.disabled === false;
        }
      ),
      { numRuns: 50 }
    );
  });
});
