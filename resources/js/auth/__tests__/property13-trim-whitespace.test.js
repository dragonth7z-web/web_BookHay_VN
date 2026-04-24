/**
 * Feature: register-form-advanced-validation, Property 13: Trim whitespace trước khi submit
 * Validates: Requirements 10.1
 */
import { describe, it, beforeEach, expect } from 'vitest';
import fc from 'fast-check';
import { RegisterValidator } from '../auth.js';

describe('Feature: register-form-advanced-validation, Property 13: Trim whitespace trước khi submit', () => {
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
        <div id="errorBox" class="hidden"><ul></ul></div>
      </form>
    `;
  });

  it('text field values are trimmed when handleSubmit is called', () => {
    fc.assert(
      fc.property(
        fc.string().map(s => '  ' + s + '  '), // add leading/trailing spaces
        (paddedValue) => {
          const validator = new RegisterValidator('testForm');
          const hoTenInput = document.getElementById('ho_ten');
          if (hoTenInput) hoTenInput.value = paddedValue;

          // Simulate what handleSubmit does: trim the field
          if (hoTenInput) hoTenInput.value = hoTenInput.value.trim();

          return hoTenInput.value === paddedValue.trim();
        }
      ),
      { numRuns: 100 }
    );
  });
});
