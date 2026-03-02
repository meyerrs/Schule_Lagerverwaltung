import apiClient from "./client";

export async function checkAuth() {
  const response = await apiClient.get("/api/isAuth");
  return response.data;
}

export async function login(username, password) {
  await apiClient.post("/api/login", { username, password });
}

export async function logout() {
  await apiClient.get("/api/logout");
}
