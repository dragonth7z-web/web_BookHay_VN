/**
 * Feature: register-form-advanced-validation, Property 8: Số điện thoại VN hợp lệ phải đúng prefix và đúng 10 số
 * Validates: Requirements 3.2, 3.3
 */
import { describe, it } from 'vitest';
import fc from 'fast-check';
import { RegisterValidator } from '../auth.js';

describe('Feature: register-form-advanced-validation, Property 8: Số điện thoại VN hợp lệ phải đúng prefix và đúng 10 số', () => {
  const validator = new RegisterValidator('nonexistent');

  it('validatePhone rejects 10-digit number not starting with 03/05/07/08/09', () => {
    fc.assert(
      fc.property(
        // Generate 10-digit strings starting with invalid prefixes (01, 02, 04, 06)
        fc.constantFrom('01', '02', '04', '06').chain(prefix =>
          fc.stringMatching(/^\d{8}$/).map(suffix => prefix + suffix)
        ),
        (phone) => {
          const result = validator.validatePhone(phone);
          return result.valid === false;
        }
      ),
      { numRuns: 100 }
    );
  });

  it('validatePhone rejects digit strings with length != 10', () => {
    fc.assert(
      fc.property(
        fc.integer({ min: 1, max: 15 }).filter(n => n !== 10).chain(len =>
          fc.stringMatching(new RegExp(`^\\d{${len}}$`))
        ),
        (phone) => {
          const result = validator.validatePhone(phone);
          return result.valid === false;
        }
      ),
      { numRuns: 100 }
    );
  });

  it('validatePhone accepts valid VN phone numbers', () => {
    fc.assert(
      fc.property(
        fc.constantFrom('03', '05', '07', '08', '09').chain(prefix =>
          fc.stringMatching(/^\d{8}$/).map(suffix => prefix + suffix)
        ),
        (phone) => {
          const result = validator.validatePhone(phone);
          return result.valid === true;
        }
      ),
      { numRuns: 100 }
    );
  });
});
