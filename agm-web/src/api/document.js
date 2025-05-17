import apiClient from './base';

export const getDocuments = (params = {}) => {
  return apiClient.get('/documents', { params });
};

export const downloadDocument = (documentId) => {
  return apiClient.get(`/document/${documentId}/download`, {
    responseType: 'blob'
  });
};

