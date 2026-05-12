/**
 * Feature: register-form-advanced-validation, Property 10: Xác nhận mật khẩu không khớp bị từ chối
 * Validates: Requirements 5.2, 5.3
 */
import { describe, it } from 'vitest';
import fc from 'fast-check';
import { RegisterValidator } from '../auth.js';

describe('Feature: register-form-advanced-validation, Property 10: Xác nhận mật khẩu không khớp bị từ chối', () => {
  const validator = new RegisterValidator('nonexistent');

  it('validateConfirm returns valid: false when password !== confirmPassword', () => {
    fc.assert(
      fc.property(
        fc.string({ minLength: 1 }),
        fc.string({ minLength: 1 }),
        (password, confirm) => {
          // Only test when they differ
          if (password === confirm) return true;
          const result = validator.validateConfirm(confirm, password);
          return result.valid === false;
        }
      ),
      { numRuns: 100 }
    );
  });

  it('validateConfirm returns valid: true when password === confirmPassword and both non-empty', () => {
    fc.assert(
      fc.property(
        fc.string({ minLength: 1 }).filter(s => s.trim() !== ''),
        (password) => {
          const result = validator.validateConfirm(password, password);
          return result.valid === true && result.error === null;
        }
      ),
      { numRuns: 100 }
    );
  });
});
