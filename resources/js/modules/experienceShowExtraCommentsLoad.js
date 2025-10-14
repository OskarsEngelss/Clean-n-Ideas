export function initExperienceShowExtraCommentsLoad() {
    const postContainer = document.querySelector(".experience-show-comments-load-trigger");
    const trigger = document.querySelector(".experience-show-comments-load-trigger");
    let page = 1;
    let isLoading = false;
    let noMorePosts = false;

    const loadMorePosts = async () => {
        if (isLoading || noMorePosts) return;
        isLoading = true;
        page++;

        try {
            const response = await fetch(`/experiences/${trigger.dataset.experienceSlug}/comments/load-more?page=${page}`);
            if (!response.ok) throw new Error("Network error");

            const data = await response.text();
            if (data.trim() === "") {
                noMorePosts = true;
                observer.disconnect();
                console.log("No more comments to load.");
                return;
            }

            postContainer.insertAdjacentHTML("beforebegin", data);
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