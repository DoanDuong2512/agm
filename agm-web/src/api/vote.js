import apiClient from './base';

export const getVotes = (params = {}) => {
  return apiClient.get('/votes', { params });
};

export const getAuthorize = (params = {}) => {
  return apiClient.get('/vote/authorize', { params });
};

export const vote = (data = {}) => {
  return apiClient.post('/vote', data );
};

export const reVote = (data = {}) => {
  return apiClient.post('/re-vote', data );
};

export const printBauCu = (params = {}) => {
  return apiClient.get('/print-bau-cu', {params} );
};

export const printBieuQuyet = (params = {}) => {
  return apiClient.get('/print-bieu-quyet', {params} );
};

export const printCoDong = (params = {}) => {
  return apiClient.get('/print-co-dong', {params} );
};
