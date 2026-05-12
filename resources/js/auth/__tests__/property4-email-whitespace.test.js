/**
 * Feature: register-form-advanced-validation, Property 4: Email có khoảng trắng bị từ chối
 * Validates: Requirements 2.1
 */
import { describe, it } from 'vitest';
import fc from 'fast-check';
import { RegisterValidator } from '../auth.js';

describe('Feature: register-form-advanced-validation, Property 4: Email có khoảng trắng bị từ chối', () => {
  const validator = new RegisterValidator('nonexistent');

  it('validateEmail rejects any string containing at least one whitespace character', () => {
    fc.assert(
      fc.property(
        fc.string(),
        fc.string(),
        fc.constantFrom(' ', '\t', '\n', '\r'),
        (before, after, space) => {
          const email = before + space + after;
          const result = validator.validateEmail(email);
          return result.valid === false;
        }
      ),
      { numRuns: 100 }
    );
  });
});
