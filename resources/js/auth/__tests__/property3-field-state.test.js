/**
 * Feature: register-form-advanced-validation, Property 3: Field state phản ánh đúng kết quả validation
 * Validates: Requirements 1.6, 1.7, 2.8, 2.9, 3.4, 3.5, 4.7, 4.8, 5.4, 5.5, 7.2, 7.3, 7.4, 7.5
 */
import { describe, it, expect, beforeEach } from 'vitest';
import fc from 'fast-check';
import { RegisterValidator } from '../auth.js';

describe('Feature: register-form-advanced-validation, Property 3: Field state phản ánh đúng kết quả validation', () => {
  beforeEach(() => {
    document.body.innerHTML = `
      <form id="testForm">
        <div class="relative group">
          <input id="testField" class="border-2 border-slate-100" />
          <span id="error-testField"></span>
        </div>
      </form>
    `;
  });

  it('setFieldState valid → border-green-500', () => {
    fc.assert(
      fc.property(
        fc.string(),
        (msg) => {
          const validator = new RegisterValidator('testForm');
          validator.setFieldState('testField', 'valid', msg);
          const input = document.getElementById('testField');
          return input.classList.contains('border-green-500') && !input.classList.contains('border-red-500');
        }
      ),
      { numRuns: 100 }
    );
  });

  it('setFieldState invalid → border-red-500', () => {
    fc.assert(
      fc.property(
        fc.string({ minLength: 1 }),
        (msg) => {
          const validator = new RegisterValidator('testForm');
          validator.setFieldState('testField', 'invalid', msg);
          const input = document.getElementById('testField');
          return input.classList.contains('border-red-500') && !input.classList.contains('border-green-500');
        }
      ),
      { numRuns: 100 }
    );
  });
});
