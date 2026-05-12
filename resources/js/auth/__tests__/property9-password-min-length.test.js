/**
 * Feature: register-form-advanced-validation, Property 9: Mật khẩu dưới 8 ký tự bị block
 * Validates: Requirements 4.2
 */
import { describe, it, expect } from 'vitest';
import fc from 'fast-check';
import { RegisterValidator } from '../auth.js';

describe('Feature: register-form-advanced-validation, Property 9: Mật khẩu dưới 8 ký tự bị block', () => {
  const validator = new RegisterValidator('nonexistent');

  it('validatePassword returns valid: false with length error for any string of length 0-7', () => {
    fc.assert(
      fc.property(
        fc.string({ minLength: 0, maxLength: 7 }),
        (password) => {
          const result = validator.validatePassword(password);
          return result.valid === false && result.error !== null && result.error.includes('8 ký tự');
        }
      ),
      { numRuns: 100 }
    );
  });

  it('validatePassword returns valid: true for any string of length >= 8', () => {
    fc.assert(
      fc.property(
        fc.string({ minLength: 8, maxLength: 50 }),
        (password) => {
          const result = validator.validatePassword(password);
          return result.valid === true && result.error === null;
        }
      ),
      { numRuns: 100 }
    );
  });
});
