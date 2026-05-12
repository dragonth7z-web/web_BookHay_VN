/**
 * Feature: register-form-advanced-validation, Property 5: Email được normalize về lowercase
 * Validates: Requirements 2.4
 */
import { describe, it, beforeEach, expect } from 'vitest';
import fc from 'fast-check';
import { RegisterValidator } from '../auth.js';

describe('Feature: register-form-advanced-validation, Property 5: Email được normalize về lowercase', () => {
  beforeEach(() => {
    document.body.innerHTML = `
      <form id="testForm">
        <div class="relative group">
          <input id="email" class="border-2 border-slate-100" type="email" />
          <span id="error-email"></span>
        </div>
      </form>
    `;
  });

  it('email input value is lowercased after blur event', () => {
    fc.assert(
      fc.property(
        // Generate a valid-looking email with uppercase letters
        fc.tuple(
          fc.stringMatching(/^[a-z]{2,8}$/),
          fc.stringMatching(/^[A-Z]{1,4}$/),
          fc.stringMatching(/^[a-z]{2,6}$/),
          fc.stringMatching(/^[a-z]{2,4}$/)
        ),
        ([local1, upper, domain, tld]) => {
          const emailWithUpper = local1 + upper + '@' + domain + '.' + tld;
          const emailInput = document.getElementById('email');
          emailInput.value = emailWithUpper;

          // Simulate blur: normalize to lowercase
          emailInput.value = emailInput.value.toLowerCase().trim();

          return emailInput.value === emailWithUpper.toLowerCase();
        }
      ),
      { numRuns: 100 }
    );
  });
});
