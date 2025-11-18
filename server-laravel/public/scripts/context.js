// This script will fetch baseContext from NurseDashboardController. It can be used across multiple pages.

async function fetchBaseContext() {
    try {
        const response = await fetch("api/nurse/context/base", {
            method: "GET",
            headers: {
                Accept: "application/json",
            },
            cache: "no-cache",
        });

        if (!response.ok) {
            console.error("BaseContext fetch failed:", response.statusText);
            return null;
        }

        const data = await response.json();
        return data;
    } catch (error) {
        console.error("Error fetching BaseContext:", error);
        return null;
    }
}

// Passive Refresh
let passiveRefreshTimer = null;

function startPassiveRefresh(callback) {
    refreshBaseContext(callback);

    // Refresh every 2 minutes
    passiveRefreshTimer = setInterval(() => {
        if (!document.hidden) {
            refreshBaseContext(callback);
        }
    }, 120000);
    console.log("passive refresh timer set");
}

function refreshBaseContext(callback) {
    fetchBaseContext().then((data) => {
        if (data && typeof callback === "function") {
            callback(data);
        }
    });
}
