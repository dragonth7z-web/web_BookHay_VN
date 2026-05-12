/**
 * Feature: register-form-advanced-validation, Property 11: ErrorBox hiển thị tối đa 3 lỗi
 * Validates: Requirements 8.1
 */
import { describe, it, expect, beforeEach } from 'vitest';
import fc from 'fast-check';
import { RegisterValidator } from '../auth.js';

describe('Feature: register-form-advanced-validation, Property 11: ErrorBox hiển thị tối đa 3 lỗi', () => {
  beforeEach(() => {
    document.body.innerHTML = `
      <form id="testForm">
        <div id="errorBox" class="hidden">
          <ul></ul>
        </div>
      </form>
    `;
  });

  it('showErrorBox renders min(N, 3) items for any N >= 1 errors', () => {
    fc.assert(
      fc.property(
        fc.array(fc.string({ minLength: 1 }), { minLength: 1, maxLength: 20 }),
        (errors) => {
          const validator = new RegisterValidator('testForm');
          validator.showErrorBox(errors);
          const items = document.querySelectorAll('#errorBox ul li');
          return items.length === Math.min(errors.length, 3);
        }
      ),
      { numRuns: 100 }
    );
  });
});
