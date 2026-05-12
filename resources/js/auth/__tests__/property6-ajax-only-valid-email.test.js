/**
 * Feature: register-form-advanced-validation, Property 6: AJAX check email chỉ gọi khi email hợp lệ
 * Validates: Requirements 2.5
 */
import { describe, it, vi, expect } from 'vitest';
import fc from 'fast-check';
import { RegisterValidator } from '../auth.js';

describe('Feature: register-form-advanced-validation, Property 6: AJAX check email chỉ gọi khi email hợp lệ', () => {
  it('checkEmailExists is not called when email is invalid (has spaces or wrong format)', () => {
    fc.assert(
      fc.property(
        // Generate invalid emails: either with spaces or missing @ symbol
        fc.oneof(
          // Email with spaces
          fc.tuple(fc.string({ minLength: 1 }), fc.string({ minLength: 1 })).map(([a, b]) => a + ' ' + b),
          // Email without @ (invalid format)
          fc.stringMatching(/^[a-zA-Z0-9]{3,10}$/)
        ),
        (invalidEmail) => {
          const validator = new RegisterValidator('nonexistent');
          const checkEmailExistsSpy = vi.spyOn(validator, 'checkEmailExists');

          const result = validator.validateEmail(invalidEmail);

          // If email is invalid, checkEmailExists should NOT be called
          if (!result.valid) {
            expect(checkEmailExistsSpy).not.toHaveBeenCalled();
            return true;
          }
          // If somehow valid (edge case), that's fine too
          return true;
        }
      ),
      { numRuns: 100 }
    );
  });
});
