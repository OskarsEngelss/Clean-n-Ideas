export function initExtraUserExperienceLoad() {
    const postContainer = document.querySelector(".profile-experience-container");
    const trigger = document.getElementById("user-load-more-trigger");
    let page = 1;
    let isLoading = false;
    let noMorePosts = false;
    const userId = trigger.dataset.userId;

    const loadMorePosts = async () => {
        if (isLoading || noMorePosts) return;
        isLoading = true;
        page++;

        try {
            const response = await fetch(`/user/${userId}/load-more?page=${page}`);
            if (!response.ok) throw new Error("Network error");

            const data = await response.text();
            console.log(data);
            if (data.trim() === "") {
                noMorePosts = true;
                observer.disconnect();
                console.log("No more posts to load.");
                return;
            }

            postContainer.insertAdjacentHTML("beforeend", data);
        } catch (error) {
            console.error("Error loading posts:", error);
        } finally {
            isLoading = false;
        }
    };

    const observer = new IntersectionObserver(
        entries => {
            if (entries[0].isIntersecting) {
                loadMorePosts();
            }
        },
        { threshold: 0.5 }
    );

    observer.observe(trigger);
}