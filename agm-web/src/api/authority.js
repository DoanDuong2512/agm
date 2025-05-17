import apiClient from './base';

// Get shareholder information
export const getShareholderInfo = () => {
  return apiClient.get('/shareholder/info');
};

// Submit authority delegation
export const submitAuthority = (delegationData) => {
  return apiClient.post('/authority/delegate', delegationData);
};

// Get authority guide or instructions
export const getAuthorityGuide = () => {
  return apiClient.get('/authority/guide');
};